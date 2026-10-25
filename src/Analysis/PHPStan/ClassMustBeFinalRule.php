<?php

declare(strict_types=1);

namespace EightFold\Architecture\PHPStan\Rules;

use EightFold\Architecture\Attributes\AllowInheritance;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Enforces that all concrete classes must be declared final unless explicitly
 * annotated with #[AllowInheritance].
 *
 * @implements Rule<Class_>
 */
final class ClassMustBeFinalRule implements Rule
{
    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * @param Class_ $node
     * @return array<\PHPStan\Rules\RuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        // Skip anonymous classes and classes already declared final
        if ($node->isAnonymous() || $node->isFinal()) {
            return [];
        }

        // Abstract classes are designed for extension
        if ($node->isAbstract()) {
            return [];
        }

        // Check for #[AllowInheritance] attribute
        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                $attrName = $attr->name->toString();
                if (
                    $attrName === AllowInheritance::class
                    || $attrName === 'AllowInheritance'
                    || str_ends_with($attrName, '\\AllowInheritance')
                ) {
                    return [];
                }
            }
        }

        $className = $node->namespacedName ? $node->namespacedName->toString() : ($node->name ? $node->name->toString() : 'Class');

        return [
            RuleErrorBuilder::message(sprintf(
                'Class "%s" must be declared final or annotated with #[AllowInheritance].',
                $className
            ))->identifier('eightfold.architecture.mustBeFinal')->build(),
        ];
    }
}
