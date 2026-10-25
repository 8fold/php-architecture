<?php

declare(strict_types=1);

namespace MTM\Analysis\PHPStan;

use FilesystemIterator;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use SplFileInfo;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ExtendedMethodReflection;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\ObjectType;

use MTM\Shared\Attributes\Repository;
use MTM\Shared\Attributes\TenantScopeExempt;
use MTM\Shared\Database\DatabaseConnection;

/**
 * Requires each tenant-scoped table queried by a repository to be scoped
 * by its own tenantId column and bound :tenantId parameter.
 *
 * The table catalogue is derived from CREATE TABLE and ALTER TABLE
 * statements in the migration source, so new tenant-scoped tables enter
 * the check without duplicating the schema list here.
 *
 * @implements Rule<MethodCall>
 */
final class TenantScopeRule implements Rule
{
    /** @var array<string, string>|null */
    private ?array $tenantScopedTables = null;

    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (
            $node->isFirstClassCallable()
            || $node->name instanceof Identifier === false
            || in_array(
                strtolower($node->name->toString()),
                ['fetchone', 'fetchall', 'fetchcolumn', 'execute', 'prepare'],
                true
            ) === false
            || new ObjectType(DatabaseConnection::class)
                ->isSuperTypeOf($scope->getType($node->var))->yes() === false
        ) {
            return [];
        }

        $class = $scope->getClassReflection();
        if (
            $class === null
            || array_any(
                $class->getAttributes(),
                static fn ($attribute): bool => $attribute->getName() === Repository::class
            ) === false
        ) {
            return [];
        }

        $method = $scope->getFunction();
        if (
            $method instanceof ExtendedMethodReflection
            && array_any(
                $method->getAttributes(),
                static fn ($attribute): bool => $attribute->getName() === TenantScopeExempt::class
            )
        ) {
            return [];
        }

        $sqlArgument = null;
        $parameterArgument = null;
        foreach ($node->getArgs() as $index => $argument) {
            $name = $argument->name?->toString();
            if ($name === 'sql' || ($name === null && $index === 0)) {
                $sqlArgument = $argument->value;
            }
            if ($name === 'params' || ($name === null && $index === 1)) {
                $parameterArgument = $argument->value;
            }
        }
        if ($sqlArgument === null) {
            return [];
        }

        $hasTenantBinding = $parameterArgument !== null
            && $scope->getType($parameterArgument)
                ->hasOffsetValueType(new ConstantStringType('tenantId'))->yes();
        $tables = self::tenantScopedTables();
        $errors = [];
        foreach ($scope->getType($sqlArgument)->getConstantStrings() as $sqlType) {
            $sql = $sqlType->getValue();
            $references = self::tenantTableReferences($sql, $tables);
            foreach ($references as $reference) {
                $hasScope = self::hasTenantScope(
                    $sql,
                    $reference,
                    count($references) === 1
                );
                $hasPlaceholder = preg_match('/:tenantId\b/', $sql) === 1;

                if ($hasScope && $hasPlaceholder && $hasTenantBinding) {
                    continue;
                }

                $errors[] = RuleErrorBuilder::message(
                    "Tenant-scoped table '{$reference['table']}' must scope its own"
                    . ' tenantId with :tenantId and bind the tenantId parameter.'
                )
                    ->identifier('mtm.tenantScope')
                    ->line($node->getStartLine())
                    ->build();
            }
        }

        return $errors;
    }

    /**
     * @param array<string, string> $tables
     * @return list<array{table: string, alias: ?string, insert: bool}>
     */
    private static function tenantTableReferences(string $sql, array $tables): array
    {
        preg_match_all(
            '/\b(FROM|JOIN|UPDATE|INTO)\s+`?([A-Za-z_][A-Za-z0-9_]*)`?'
            . '(?:\s+(?:AS\s+)?`?([A-Za-z_][A-Za-z0-9_]*)`?)?/i',
            $sql,
            $matches,
            PREG_SET_ORDER
        );

        $reserved = [
            'AS', 'WHERE', 'SET', 'ON', 'VALUES', 'ORDER', 'GROUP', 'LIMIT',
            'INNER', 'LEFT', 'RIGHT', 'CROSS', 'JOIN', 'UNION', 'FOR',
        ];
        $references = [];

        foreach ($matches as $match) {
            $table = $match[2];
            $key = strtolower($table);
            if (isset($tables[$key]) === false) {
                continue;
            }

            $alias = $match[3] ?? null;
            if ($alias !== null && in_array(strtoupper($alias), $reserved, true)) {
                $alias = null;
            }

            $references[] = [
                'table' => $tables[$key],
                'alias' => $alias,
                'insert' => strtoupper($match[1]) === 'INTO',
            ];
        }

        return $references;
    }

    /**
     * @param array{table: string, alias: ?string, insert: bool} $reference
     */
    private static function hasTenantScope(
        string $sql,
        array $reference,
        bool $allowUnqualified
    ): bool
    {
        if ($reference['insert']) {
            if (preg_match(
                '/\bINTO\s+`?[A-Za-z_][A-Za-z0-9_]*`?\s*'
                . '\(([^)]*)\)\s*(?i:VALUES)\s*\(([^)]*)\)/s',
                $sql,
                $insert
            ) !== 1) {
                return false;
            }

            $columns = array_map(
                static fn (string $column): string => strtolower(
                    trim($column, " \t\n\r\0\x0B`")
                ),
                explode(',', $insert[1])
            );
            $tenantIdIndex = array_search('tenantid', $columns, true);
            if ($tenantIdIndex === false) {
                return false;
            }

            $values = array_map('trim', explode(',', $insert[2]));

            return ($values[$tenantIdIndex] ?? null) === ':tenantId';
        }

        if ($reference['alias'] !== null) {
            $qualifier = preg_quote($reference['alias'], '/') . '\s*\.\s*';
        } else {
            $table = preg_quote($reference['table'], '/') . '\s*\.\s*';
            $qualifier = $allowUnqualified ? '(?:' . $table . ')?' : $table;
        }

        return preg_match(
            '/\b' . $qualifier . '`?tenantId`?\s*=\s*:tenantId\b/',
            $sql
        ) === 1;
    }

    /**
     * @return array<string, string> lowercase table name => migration spelling
     */
    private function tenantScopedTables(): array
    {
        if ($this->tenantScopedTables !== null) {
            return $this->tenantScopedTables;
        }

        $migrations = dirname(__DIR__, 2) . '/migrations';
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($migrations, FilesystemIterator::SKIP_DOTS)
        );
        $tables = [];

        foreach ($files as $file) {
            if (
                $file instanceof SplFileInfo === false
                || $file->isFile() === false
                || $file->getExtension() !== 'php'
            ) {
                continue;
            }

            $source = file_get_contents($file->getPathname());
            if ($source === false) {
                throw new RuntimeException(
                    'Cannot read migration file: ' . $file->getPathname()
                );
            }

            preg_match_all(
                '/CREATE\s+TABLE(?:\s+IF\s+NOT\s+EXISTS)?\s+`?([A-Za-z_][A-Za-z0-9_]*)`?'
                . '\s*\((.*?)\)\s*ENGINE\b/is',
                $source,
                $creates,
                PREG_SET_ORDER
            );

            foreach ($creates as $create) {
                if (preg_match('/^\s*`tenantId`\s+/mi', $create[2]) === 1) {
                    $tables[strtolower($create[1])] = $create[1];
                }
            }

            preg_match_all(
                '/ALTER\s+TABLE\s+`?([A-Za-z_][A-Za-z0-9_]*)`?([^;]*);/is',
                $source,
                $alters,
                PREG_SET_ORDER
            );

            foreach ($alters as $alter) {
                if (preg_match('/\bADD(?:\s+COLUMN)?\s+`tenantId`\s+/i', $alter[2]) === 1) {
                    $tables[strtolower($alter[1])] = $alter[1];
                }
            }
        }

        if ($tables === []) {
            throw new RuntimeException(
                'No tenant-scoped tables were discovered in migration sources under '
                . $migrations
            );
        }

        $this->tenantScopedTables = $tables;

        return $tables;
    }
}
