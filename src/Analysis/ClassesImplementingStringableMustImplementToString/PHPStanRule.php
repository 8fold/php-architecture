<?php

declare(strict_types=1);

namespace Eightfold\Analysis\ClassesImplementingStringableMustImplementToString;

use Eightfold\Analysis\MessageCompiler;
use Eightfold\Interfaces\ToString;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Stringable;

/**
 * @implements Rule<ClassLike>
 */
final class PHPStanRule implements Rule
{
    public function getNodeType(): string
    {
        return ClassLike::class;
    }

    /**
     * @param ClassLike $node
     * @return list<IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (($node instanceof Class_) === false) {
            return [];
        }

        if (self::directlyImplements($node, $scope, ToString::class)) {
            return [];
        }

        if (
            self::directlyImplements($node, $scope, Stringable::class) === false
            && self::declaresMagicMethod($node) === false
        ) {
            return [];
        }

        return [
            RuleErrorBuilder::message(self::compiler()->compile('message'))->tip(
                self::compiler()->compile('help')
            )->line(
                $node->getStartLine()
            )->identifier(
                self::identifier()
            )->nonIgnorable()->build(),
        ];
    }

    private static function directlyImplements(Class_ $node, Scope $scope, string $interface): bool
    {
        foreach ($node->implements as $name) {
            if (strcasecmp($scope->resolveName($name), $interface) === 0) {
                return true;
            }
        }

        return false;
    }

    private static function declaresMagicMethod(Class_ $node): bool
    {
        foreach ($node->getMethods() as $method) {
            if (strcasecmp($method->name->toString(), '__toString') === 0) {
                return true;
            }
        }

        return false;
    }

    private static function compiler(): MessageCompiler
    {
        return new MessageCompiler(__DIR__);
    }

    private static function identifier(): string
    {
        return 'eightfold.' . lcfirst(basename(__DIR__));
    }
}
