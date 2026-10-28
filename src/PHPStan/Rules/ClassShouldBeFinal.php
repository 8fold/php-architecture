<?php

namespace Eightfold\PHPStan\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

use Eightfold\PHPStan\Rules\ClassMustBeFinal;

use Eightfold\Attributes\BaseClass;

/**
 * @implements Rule<InClassNode>
 */
final class ClassShouldBeFinal implements Rule
{
    private const string OPT_OUT_ATTRIBUTE = BaseClass::class;

    public static function message(string $className): string
    {
        return <<<message
        Class {$className} should be declared as final.
        message;
    }

    public static function tip(string $className, bool $isInclude = false): string
    {
        $attributeName = self::OPT_OUT_ATTRIBUTE;

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

        return ClassMustBeFinal::errorFoundFor($node);
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
                self::ruleError($node)
            ];
        }
        return [];
    }
}
