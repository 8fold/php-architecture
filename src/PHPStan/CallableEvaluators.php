<?php

declare(strict_types=1);

namespace Eightfold\PHPStan;

use PhpParser\Node;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\ArrowFunction;
use PHPStan\Analyser\Scope;

use Eightfold\Attributes\CallableBaseType;
use Eightfold\Attributes\CallableSubtype;
use Eightfold\Attributes\Returns;

final class CallableEvaluators
{
    public static function isMethod(Node $node): bool
    {
        return $node instanceof ClassMethod;
    }

    public static function isClosure(Node $node): bool
    {
        if ($node instanceof Closure) {
            return true;
        }
        return self::isArrowFunction($node);
    }

    public static function isArrowFunction(Node $node): bool
    {
        return $node instanceof ArrowFunction;
    }

    public static function isAbstract(Node $node, Scope $scope): bool
    {
        if (self::isMethod($node) === false) {
            return false;
        }

        if ($node->isAbstract()) {
            return true;
        }
        return $scope->isInClass() and $scope->getClassReflection()?->isInterface();
    }

    public static function isMagicMethod(Node $node): bool
    {
        if (self::isMethod($node) === false) {
            return false;
        }
        return in_array(
            strtolower($node->name->toString()),
            CallableBaseType::magicMethodsList(),
            true
        );
    }

    public static function isStatic(Node $node): bool
    {
        if (self::isMethod($node) === false) {
            return false;
        }
        return $node->isStatic();
    }

    /**
     * Resolves the AST return type declaration of a FunctionLike node into a Returns enum.
     */
    public static function returns(FunctionLike $node, Scope $scope): Returns
    {
        $returnTypeNode = $node->getReturnType();

        if ($returnTypeNode === null) {
            return Returns::Other;
        }

        $typeName = null;

        if ($returnTypeNode instanceof Node\Identifier) {
            $typeName = strtolower($returnTypeNode->name);
        } elseif ($returnTypeNode instanceof Node\Name) {
            $typeName = strtolower($returnTypeNode->toString());
        }

        return match ($typeName) {
            'void'           => Returns::VoidType,
            'never'          => Returns::NeverType,
            'bool'           => Returns::BooleanType,
            'self', 'static' => Returns::SelfType,
            default          => Returns::Other,
        };
    }

    /**
     * Checks if the callable node is decorated with the #[\NoDiscard] attribute.
     */
    public static function hasNoDiscard(FunctionLike $node): bool
    {
        foreach ($node->getAttrGroups() as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                $shortName = $attr->name->getLast();
                if ($shortName === 'NoDiscard' || $attr->name->toString() === 'NoDiscard') {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Checks if the callable node is decorated with the #[Pure] attribute.
     */
    public static function isPure(FunctionLike $node): bool
    {
        foreach ($node->getAttrGroups() as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                $shortName = $attr->name->getLast();
                if ($shortName === 'Pure' || $attr->name->toString() === 'Pure') {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Evaluates a FunctionLike node to determine CallableBaseType.
     */
    public static function callableBaseTypeFor(
        FunctionLike $node,
        Scope $scope
    ): CallableBaseType {
        return CallableBaseType::fromFlags(
            isMethod: self::isMethod($node),
            isClosure: self::isClosure($node),
            isAbstract: self::isAbstract($node, $scope),
            isMagic: self::isMagicMethod($node),
            isStatic: self::isStatic($node)
        );
    }

    /**
     * Evaluates a FunctionLike node and infers its structural CallableSubtype.
     */
    public static function callableSubtypeFor(
        FunctionLike $node,
        Scope $scope
    ): CallableSubtype {
        $baseType = self::callableBaseTypeFor($node, $scope);

        return CallableSubtype::fromFlags(
            baseType: $baseType,
            returns: self::returns($node, $scope),
            hasNoDiscard: self::hasNoDiscard($node),
            isPure: self::isPure($node)
        );
    }

    /**
     * Returns a human-readable reason string explaining the inferred CallableSubtype.
     */
    public static function callableSubtypeReasonFor(
        FunctionLike $node,
        Scope $scope
    ): ?string {
        $returns = self::returns($node, $scope);
        $hasNoDiscard = self::hasNoDiscard($node);
        $isPure = self::isPure($node);

        if ($node instanceof ClassMethod && $node->isAbstract()) {
            return 'abstract contract signature';
        }

        return match ($returns) {
            Returns::VoidType => 'returns void',
            Returns::NeverType => 'returns never',
            Returns::BooleanType => $hasNoDiscard ? 'returns bool with #[\NoDiscard]' : 'returns bool',
            Returns::SelfType => match (self::isStatic($node)) {
                true => 'static initializer returning self',
                false => $isPure ? 'pure instance method returning clone' : 'fluent instance method returning $this',
            },
            Returns::Other => match (true) {
                $hasNoDiscard => 'returns value with #[\NoDiscard]',
                $isPure => 'decorated with #[Pure]',
                default => 'returns non-void value',
            },
        };
    }
}
