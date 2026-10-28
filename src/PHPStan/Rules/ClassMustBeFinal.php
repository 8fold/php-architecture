<?php

namespace Eightfold\PHPStan\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

use Eightfold\Attributes\BaseClass;

/**
 * @implements Rule<InClassNode>
 */
#[BaseClass('Testing the BaseClass attribute-based opt-out capability')]
class ClassMustBeFinal implements Rule
{
    public static function message(string $className): string
    {
        return <<<message
        Class {$className} must be declared as final.
        message;
    }

    public static function tip(string $className, bool $isInclude = false): string
    {
        $className = $className
            |> (fn($c) => explode('\\', $c))
            |> 'array_last';

        $base = <<<base
        Change declaration to `final class {$className}`
        base;

        if ($isInclude) {
            return <<<tip
            {$base} (recommended);
            tip;
        }
        return $base;
    }

    public static function errorFoundFor(InClassNode $node): bool
    {
        // Skip interfaces, traits, enums, anonymous classes, abstract classes,
        // and classes marked final
        $classReflection = $node->getClassReflection();
        if (
            $classReflection->isInterface()
            || $classReflection->isTrait()
            || $classReflection->isEnum()
            || $classReflection->isAnonymous()
            || $classReflection->isAbstract()
        ) {
            return false;
        }

        // The only way this rule is broken
        return $classReflection->isFinal() === false;
    }

    public static function ruleError(InClassNode $node): IdentifierRuleError
    {
        $id = static::class
            |> (fn($c) => explode('\\', $c))
            |> 'array_last'
            |> 'lcfirst'
            |> (fn($s) => 'eightfold.' . $s);

        $className = $node->getClassReflection()->getName();
        return RuleErrorBuilder::message(
            self::message($className)
        )->tip(
            self::tip($className, false)
        )->line(
            $node->getOriginalNode()->getStartLine()
        )->identifier($id)->nonIgnorable()->build();
    }

    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    /**
     * @param InClassNode $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (self::errorFoundFor($node)) {
            return [
                self::ruleError($node),
            ];
        }
        return [];
    }
}
