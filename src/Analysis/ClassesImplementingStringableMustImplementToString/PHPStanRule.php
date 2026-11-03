<?php

declare(strict_types=1);

namespace Eightfold\Analysis\ClassesImplementingStringableMustImplementToString;

use Eightfold\Analysis\MessageCompiler;
use Eightfold\Interfaces\ToString;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Stringable;

/**
 * @implements Rule<ClassLike|InClassNode>
 */
final class PHPStanRule implements Rule
{
    public function __construct(private readonly bool $includeIndirect = false)
    {
    }

    public function getNodeType(): string
    {
        return $this->includeIndirect ? InClassNode::class : ClassLike::class;
    }

    /**
     * @param ClassLike|InClassNode $node
     * @return list<IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $class = $node instanceof InClassNode ? $node->getOriginalNode() : $node;
        if (($class instanceof Class_) === false) {
            return [];
        }

        if ($node instanceof InClassNode) {
            $reflection = $node->getClassReflection();
            if ($reflection->implementsInterface(ToString::class)) {
                return [];
            }
            if (
                $reflection->implementsInterface(Stringable::class) === false
                && $reflection->hasNativeMethod('__toString') === false
            ) {
                return [];
            }
        } else {
            if (self::directlyImplements($class, $scope, ToString::class)) {
                return [];
            }
            if (
                self::directlyImplements($class, $scope, Stringable::class) === false
                && self::declaresMagicMethod($class) === false
            ) {
                return [];
            }
        }

        return [
            RuleErrorBuilder::message(self::compiler()->compile('message'))->tip(
                self::compiler()->compile('help')
            )->line(
                $class->getStartLine()
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
