<?php

namespace Eightfold\Analysis\ClassesMustBeFinal;

use Eightfold\Analysis\MessageCompiler;
use Eightfold\Attributes\Classes\BaseClass;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<InClassNode>
 */
#[BaseClass(rationale: 'Testing the BaseClass attribute-based opt-out capability')]
class PHPStanRule implements Rule
{
    public static function message(string $className): string
    {
        return self::compiler()->compile('message', ['className' => $className]);
    }

    public static function tip(string $className, bool $isInclude = false): string
    {
        $shortClassName = array_last(explode('\\', $className));
        $tip = self::compiler()->compile(
            'help',
            ['shortClassName' => $shortClassName]
        );

        if ($isInclude) {
            return $tip . ' (recommended);';
        }

        return $tip;
    }

    public static function errorFoundFor(InClassNode $node): bool
    {
        $classReflection = $node->getClassReflection();
        if (
            $classReflection->isInterface()
            || $classReflection->isTrait()
            || $classReflection->isEnum()
            || $classReflection->isAnonymous()
            || $classReflection->isAbstract()
        ) {
            return false;
        }

        return $classReflection->isFinal() === false;
    }

    public static function ruleError(InClassNode $node): IdentifierRuleError
    {
        $className = $node->getClassReflection()->getName();

        return RuleErrorBuilder::message(self::message($className))->tip(
            self::tip($className)
        )->line(
            $node->getOriginalNode()->getStartLine()
        )->identifier(
            self::identifier()
        )->nonIgnorable()->build();
    }

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
        if (self::errorFoundFor($node)) {
            return [self::ruleError($node)];
        }

        return [];
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
