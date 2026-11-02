<?php

declare(strict_types=1);

namespace Eightfold\PHPStan;

/**
 * Experimental draft; not autoloaded or supported.
 *
 * AST Node Inspector for Method & Function Properties
 */
final readonly class NodeInspector
{
    private NodeFinder $finder;

    public function __construct()
    {
        $this->finder = new NodeFinder();
    }

    public function isStandalone(Node $node): bool
    {
        return $node instanceof Function_;
    }

    public function isStatic(Node $node): bool
    {
        if ($node instanceof ClassMethod) {
            return $node->isStatic();
        }
        if ($node instanceof Closure) {
            return $node->isStatic;
        }
        return false;
    }

    public function isFinal(Node $node): bool
    {
        return $node instanceof ClassMethod && $node->isFinal();
    }

    public function isAbstract(Node $node): bool
    {
        return $node instanceof ClassMethod && $node->isAbstract();
    }

    public function accessesThis(Node $node): bool
    {
        if (self::isStatic($node) || self::isStandalone($node)) {
            return false;
        }

        $thisFetches = $this->finder->find($node, function (Node $n) {
            return $n instanceof Node\Expr\Variable && $n->name === 'this';
        });

        return count($thisFetches) > 0;
    }

    public function mutatesInstanceState(Node $node): bool
    {
        if (self::isStatic($node) || self::isStandalone($node)) {
            return false;
        }

        $assignments = $this->finder->find($node, function (Node $n) {
            return $n instanceof Assign 
                && $n->var instanceof PropertyFetch 
                && $n->var->var instanceof Node\Expr\Variable 
                && $n->var->var->name === 'this';
        });

        return count($assignments) > 0;
    }

    public function yieldsGenerator(Node $node): bool
    {
        $yields = $this->finder->find($node, fn(Node $n) => $n instanceof Yield_);
        return count($yields) > 0;
    }

    public function getDeclaredSubtypeAttribute(Node $node): ?string
    {
        if (($node instanceof ClassMethod) === false && ($node instanceof Function_) === false) {
            return null;
        }

        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                $name = $attr->name->toString();
                $shortName = basename(str_replace('\\', '/', $name));
                if (in_array($shortName, [
                    'Command', 'CommandWithStatus', 'Query', 'StateTransformer',
                    'StateTransformerThis', 'Factory', 'NamedConstructor', 'Handler',
                    'Assertion', 'Predicate', 'Check', 'StrictPure', 'Middleware', 'Voter'
                ], true)) {
                    return $shortName;
                }
            }
        }

        return null;
    }

    public function detectBaseType(Node $node): string
    {
        if ($node instanceof Function_) {
            return 'Standalone Function';
        }
        if ($node instanceof Closure) {
            return 'Closure';
        }
        if ($node instanceof ClassMethod) {
            $name = $node->name->toString();
            if (str_starts_with($name, '__')) {
                return match ($name) {
                    '__construct' => '__construct',
                    '__toString'  => '__toString',
                    '__get'       => '__get',
                    '__set'       => '__set',
                    '__isset'     => '__isset',
                    '__unset'     => '__unset',
                    '__clone'     => '__clone',
                    '__invoke'    => '__invoke',
                    '__callStatic'=> '__callStatic',
                    default       => 'Instance Method',
                };
            }
            if ($node->isAbstract()) {
                return 'Abstract Method';
            }
            if ($node->isStatic()) {
                return 'Static Method';
            }
            return 'Instance Method';
        }

        return 'Instance Method';
    }
    
    
}