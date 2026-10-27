<?php

namespace Eightfold\PHPStan\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Rules\RuleError;

use Eightfold\PHPStan\Rules\ClassMustBeFinal;

use Eightfold\Attributes\BaseClass;

/**
 * @implements Rule<InClassNode>
 */
final class ClassShouldBeFinal implements Rule
{
    private const string OPT_OUT_ATTRIBUTE = BaseClass::class;

    public static function message(): string
    {
        return <<<message
        class should be declared as final.
        message;
    }

    public static function tip(): string
    {
        $attributeName = self::OPT_OUT_ATTRIBUTE;

        $inherited = substr_replace(ClassMustNotBeSubclass::tip(), ';', 1);

        return <<<tip
        {$inherited}
                or, decorate the class with the {$attributeName} attribute.
        tip;
    }

    public static function errorFoundFor(Node $node): bool
    {
        if (ClassMustBeFinal::errorFoundFor($node) === false) {
            return false;
        }

        $nativeReflection = $node->getClassReflection()->getNativeReflection();
        $attributes = $nativeReflection->getAttributes(
            self::OPT_OUT_ATTRIBUTE
        );

        // Check if opt-out attribute was found
        return count($attributes) === 0;
    }

    public static function ruleError(int $startLine): RuleError
    {
        $id = static::class
            |> (fn($c) => explode('\\', $c))
            |> 'array_last'
            |> 'lcfirst'
            |> (fn($s) => 'eightfold.' . $s);

        return RuleErrorBuilder::message(
            self::message()
        )->tip(
            self::tip()
        )->identifier(
            $id
        )->line($startLine)->nonIgnorable()->build();
    }

    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (self::errorFoundFor($node)) {
            return [
                self::ruleError($node->getOriginalNode()->getStartLine())
            ];
        }
        return [];
    }
}
