<?php

declare(strict_types=1);

namespace Eightfold\Analysis\ClassesImplementingStringableMustImplementToString;

use Eightfold\Analysis\MessageCompiler;
use Eightfold\Interfaces\ToString;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Stringable;

/**
 * @implements Rule<InClassNode>
 */
final class PHPStanRule implements Rule
{
    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    /**
     * @param InClassNode $node
     * @return list<IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $class = $node->getOriginalNode();
        if (($class instanceof Class_) === false) {
            return [];
        }

        $reflection = $node->getClassReflection();
        if ($reflection->implementsInterface(ToString::class)) {
            return [];
        }
        if (
            $reflection->implementsInterface(Stringable::class) === false
            && $reflection->hasNativeMethod('__toString') === false
        ) {
            return [];
        }

        return [
            RuleErrorBuilder::message(self::compiler()->compile('message'))->tip(
                self::compiler()->compile('help')
            )->line(
                $class->getStartLine()
            )->identifier(
                self::identifier()
            )->nonIgnorable()->build(),
        ];
    }

    private static function compiler(): MessageCompiler
    {
        return new MessageCompiler(__DIR__);
    }

    private static function identifier(): string
    {
        return 'eightfold.' . lcfirst(basename(__DIR__));
    }
}
