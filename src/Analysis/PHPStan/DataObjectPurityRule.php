<?php

declare(strict_types=1);

namespace EightFold\Architecture\PHPStan\Rules;

use EightFold\Architecture\Attributes\DataObject;
use EightFold\Architecture\Attributes\ValueObject;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Enforces the purity contract on classes marked with #[DataObject] or #[ValueObject].
 *
 * Checks:
 * 1. No public instance methods other than __construct().
 * 2. All properties must be readonly or use get-only property hooks.
 */
final class DataObjectPurityRule implements Rule
{
    // TODO: Is there a way for RecordPurityRule and ValueObjectPurityRule to
    // inherit or call the methods in this class?
    public function getNodeType(): string
    {
        return Class_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        /** @var Class_ $node */
        if (!$this->hasDataObjectAttribute($node)) {
            return [];
        }

        $errors = [];

        $className = $node->name->toString();

        // 1. Check public instance methods
        foreach ($node->getMethods() as $method) {
            if ($method->isPublic() && !$method->isStatic()) {
                $methodName = $method->name->toString();
                if ($methodName !== '__construct') {
                    $errors[] = RuleErrorBuilder::message(
                        "Class {$className} carries #[DataObject]/#[ValueObject] " .
                        "and cannot have public instance method \"{$methodName}\". " .
                        "Only __construct() is allowed."
                    )->line($method->getLine())->build();
                }
            }
        }

        // 2. Verify property readonly status
        foreach ($node->getProperties() as $property) {
            $propertyName = $property->props[0]->name->toString();
            if ($property->isPublic() && !$property->isReadonly()) {
                $errors[] = RuleErrorBuilder::message(
                    "Property {$propertyName} in DataObject {$className} must " .
                    "be declared readonly."
                )->line($property->getLine())->build();
            }
        }

        return $errors;
    }

    private function hasDataObjectAttribute(Class_ $node): bool
    {
        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                $name = $attr->name->toString();
                if (
                    $name === DataObject::class ||
                    $name === ValueObject::class ||
                    str_ends_with($name, '\\DataObject') ||
                    str_ends_with($name, '\\ValueObject')
                ) {
                    return true;
                }
            }
        }

        return false;
    }
}
