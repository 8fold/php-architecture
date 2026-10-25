<?php

declare(strict_types=1);

namespace MTM\Analysis\PHPStan;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Enforces the method-call scope convention: self:: addresses methods
 * defined in the class definition itself (traits included), parent::
 * addresses inherited methods, and $this-> is reserved for property
 * access.
 *
 * Improves local reasoning:
 * - $this-> lets developers know they are requesting data, not executing behavior.
 * - self:: lets developers know the method is defined in the same file/class.
 * - parent:: lets developers know the method is defined by an inherited file/class.
 *
 * @implements Rule<Node>
 */
final class MethodScopeConventionRule implements Rule
{
    public function getNodeType(): string
    {
        return Node::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if ($node instanceof MethodCall) {
            return self::checkMethodCall($node);
        }

        if ($node instanceof StaticCall) {
            return self::checkStaticCall($node, $scope);
        }

        return [];
    }

    /** @return list<\PHPStan\Rules\IdentifierRuleError> */
    private function checkMethodCall(MethodCall $node): array
    {
        if (
            $node->var instanceof Variable === false
            || $node->var->name !== 'this'
            || $node->name instanceof Identifier === false
        ) {
            return [];
        }

        return [
            RuleErrorBuilder::message(
                'Call methods with self:: (defined in this class) or'
                . ' parent:: (inherited); $this-> is reserved for'
                . ' property access.'
            )
                ->identifier('ef-arch.methodScope')
                ->build(),
        ];
    }

    /** @return list<\PHPStan\Rules\IdentifierRuleError> */
    private function checkStaticCall(StaticCall $node, Scope $scope): array
    {
        if (
            $node->class instanceof Name === false
            || strtolower($node->class->toString()) !== 'self'
            || $node->name instanceof Identifier === false
        ) {
            return [];
        }

        $class = $scope->getClassReflection();
        if ($class === null) {
            return [];
        }

        $methodName = $node->name->toString();
        if ($class->hasNativeMethod($methodName) === false) {
            return [];
        }

        $declaring = $class->getNativeMethod($methodName)
            ->getDeclaringClass()->getName();
        if ($declaring === $class->getName()) {
            return [];
        }

        return [
            RuleErrorBuilder::message(
                "self::{$methodName}() is declared in {$declaring};"
                . " use parent::{$methodName}()."
            )
                ->identifier('ef-arch.methodScope')
                ->build(),
        ];
    }
}
