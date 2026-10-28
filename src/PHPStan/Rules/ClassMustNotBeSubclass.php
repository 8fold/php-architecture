<?php

namespace Eightfold\PHPStan\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<InClassNode>
 */
final class ClassMustNotBeSubclass implements Rule
{
    public static function message(string $className): string
    {
        return <<<message
        Class {$className} must not extend another class.
        message;
    }

    public static function tip(string $className, bool $isInclude = false): string
    {
        $className = $className
            |> (fn($c) => explode('\\', $c))
            |> 'array_last';

        $base = <<<base
        Wrap the target class inside a dedicated class (adapter) (recommended);
            \tor, inject an instance via constructor (composition);
            \tor, move shared behavior into a PHP `trait` (horizontal composition)
        base;

        if ($isInclude) {
            return <<<tip
            {$base};
            tip;
        }
        return $base;
    }

    public static function errorFoundFor(InClassNode $node): bool
    {
        // Skip interfaces, traits, enums, anonymous classes
        $classReflection = $node->getClassReflection();
        if (
            $classReflection->isInterface()
            || $classReflection->isTrait()
            || $classReflection->isEnum()
            || $classReflection->isAnonymous()
        ) {
            return false;
        }

        // Check if class extends another class
        return $classReflection->getParentClass() !== null;
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
