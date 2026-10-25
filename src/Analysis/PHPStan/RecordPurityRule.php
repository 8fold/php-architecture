<?php

declare(strict_types=1);

namespace Eightfold\Analysis\PHPStan;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

use MTM\Shared\Attributes\Record;

/**
 * Enforces the record-object purity contract (docs/data-objects.md) on
 * every class carrying the #[Record] attribute:
 * promoted readonly properties and get-only property hooks only,
 * a single public constructor, and no static methods — PHP 8.5 chains
 * from new, so static initializers are ceremony pretending to be safety.
 *
 * ValueObject (#[ValueObject]) and generic (#[DataObject]) classes are deliberately
 * unchecked for now: existing value objects legitimately carry boundary
 * queries (Uuid7::isValid(), __toString()), and where "no behavior" ends
 * for them is a definitional decision to make at the last responsible
 * moment —
 * when a violation actually argues for the line.
 *
 * Attribute detection is AST-level (resolved names, not runtime
 * reflection), so fixtures and CI exercise the rule without autoloading
 * the classes under analysis.
 *
 * @implements Rule<InClassNode>
 */
final class RecordPurityRule implements Rule
{
    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if ($node instanceof InClassNode === false) {
            return [];
        }

        $class = $node->getOriginalNode();
        if (
            $class instanceof Class_ === false
            || self::carriesRecordAttribute($class, $scope) === false
        ) {
            return [];
        }

        $name = (string) ($class->namespacedName ?? $class->name);

        return [
            ...self::checkMethods($class, $name),
            ...self::checkProperties($class, $name),
        ];
    }

    private static function carriesRecordAttribute(Class_ $class, Scope $scope): bool
    {
        foreach ($class->attrGroups as $group) {
            foreach ($group->attrs as $attribute) {
                if ($scope->resolveName($attribute->name) === Record::class) {
                    return true;
                }
            }
        }

        return false;
    }

    /** @return list<\PHPStan\Rules\IdentifierRuleError> */
    private static function checkMethods(Class_ $class, string $name): array
    {
        $errors = [];

        foreach ($class->getMethods() as $method) {
            $methodName = $method->name->toString();

            if ($method->isStatic()) {
                $errors[] = RuleErrorBuilder::message(
                    "#[Record] {$name} declares static {$methodName}();"
                    . ' record objects keep strict purity — no static'
                    . ' initializers, call the public constructor directly'
                    . ' (docs/data-objects.md).'
                )
                    ->identifier('ef-arch.recordStaticMethod')
                    ->line($method->getStartLine())
                    ->build();

                continue;
            }

            if (strtolower($methodName) !== '__construct') {
                $errors[] = RuleErrorBuilder::message(
                    "#[Record] {$name} declares {$methodName}(); record"
                    . ' objects are promoted properties and property'
                    . ' hooks only — behavior belongs elsewhere'
                    . ' (docs/data-objects.md).'
                )
                    ->identifier('ef-arch.recordMethod')
                    ->line($method->getStartLine())
                    ->build();

                continue;
            }

            if ($method->isPublic() === false) {
                $errors[] = RuleErrorBuilder::message(
                    "#[Record] {$name} must have a public constructor."
                )
                    ->identifier('ef-arch.recordConstructorVisibility')
                    ->line($method->getStartLine())
                    ->build();
            }

            $errors = [...$errors, ...self::checkConstructorParams($method, $name)];
        }

        return $errors;
    }

    /** @return list<\PHPStan\Rules\IdentifierRuleError> */
    private static function checkConstructorParams(ClassMethod $constructor, string $name): array
    {
        $errors = [];

        foreach ($constructor->params as $param) {
            $promoted = $param->flags !== 0;
            $readonly = ($param->flags & Modifiers::READONLY) !== 0;

            if ($promoted && $readonly === false) {
                $paramName = '$' . (string) ($param->var->name ?? '?');
                $errors[] = RuleErrorBuilder::message(
                    "#[Record] {$name} promotes {$paramName} without"
                    . ' readonly; record properties are readonly values.'
                )
                    ->identifier('ef-arch.recordPromotedProperty')
                    ->line($param->getStartLine())
                    ->build();
            }
        }

        return $errors;
    }

    /** @return list<\PHPStan\Rules\IdentifierRuleError> */
    private static function checkProperties(Class_ $class, string $name): array
    {
        $errors = [];

        foreach ($class->getProperties() as $property) {
            $propertyName = '$' . $property->props[0]->name->toString();

            if ($property->isStatic()) {
                $errors[] = RuleErrorBuilder::message(
                    "#[Record] {$name} declares static {$propertyName};"
                    . ' record objects hold row values, not shared state.'
                )
                    ->identifier('ef-arch.recordStaticProperty')
                    ->line($property->getStartLine())
                    ->build();

                continue;
            }

            if ($property->hooks !== []) {
                foreach ($property->hooks as $hook) {
                    if ($hook->name->toString() === 'set') {
                        $errors[] = RuleErrorBuilder::message(
                            "#[Record] {$name} defines a set hook on"
                            . " {$propertyName}; record property hooks"
                            . ' are get-only views — mutate by'
                            . ' constructing a new instance.'
                        )
                            ->identifier('ef-arch.recordSetHook')
                            ->line($hook->getStartLine())
                            ->build();
                    }
                }

                continue;
            }

            if ($property->isReadonly() === false) {
                $errors[] = RuleErrorBuilder::message(
                    "#[Record] {$name} declares mutable {$propertyName};"
                    . ' record properties are promoted readonly or'
                    . ' get-only property hooks.'
                )
                    ->identifier('ef-arch.recordMutableProperty')
                    ->line($property->getStartLine())
                    ->build();
            }
        }

        return $errors;
    }
}
