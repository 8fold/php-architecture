<?php

namespace Eightfold\PHPStan\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

use Eightfold\Attributes\Subclass;

/**
 * @implements Rule<InClassNode>
 */
final class ClassShouldNotBeSubclass implements Rule
{
    private const string OPT_OUT_ATTRIBUTE = Subclass::class;

    public static function message(string $className): string
    {
        return <<<message
        Class {$className} should not extend another class.
        message;
    }

    public static function tip(string $className, bool $isInclude = false): string
    {
        $attributeName = Subclass::class;

        $className = $className
            |> (fn($c) => explode('\\', $c))
            |> 'array_last';

        $inherited = ClassMustNotBeSubclass::tip($className, true);

        return <<<tip
        {$inherited}
            \tor, decorate the class with the {$attributeName} attribute.
        tip;
    }

    public static function errorFoundFor(InClassNode $node): bool
    {
        // Check if opt-out attribute was found
        $nativeReflection = $node->getClassReflection()->getNativeReflection();
        $attributes = $nativeReflection->getAttributes(
            self::OPT_OUT_ATTRIBUTE
        );
        if ($attributes !== []) {
            return false;
        }

        return ClassMustNotBeSubclass::errorFoundFor($node);
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
