<?php

namespace Eightfold\Analysis\ClassesShouldNotBeSubclass;

use Eightfold\Analysis\ClassesMustNotBeSubclass\PHPStanRule as ClassesMustNotBeSubclassRule;
use Eightfold\Analysis\MessageCompiler;
use Eightfold\Attributes\Classes\Subclass;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<InClassNode>
 */
final class PHPStanRule implements Rule
{
    private const string OPT_OUT_ATTRIBUTE = Subclass::class;

    public static function message(string $className): string
    {
        return self::compiler()->compile('message', ['className' => $className]);
    }

    public static function tip(string $className, bool $isInclude = false): string
    {
        $mustTip = ClassesMustNotBeSubclassRule::tip($className, true);

        return self::compiler()->compile(
            'help',
            [
                'mustTip' => $mustTip,
                'attributeName' => self::OPT_OUT_ATTRIBUTE,
            ]
        );
    }

    public static function errorFoundFor(InClassNode $node): bool
    {
        $attributes = $node->getClassReflection()
            ->getNativeReflection()
            ->getAttributes(self::OPT_OUT_ATTRIBUTE);
        if ($attributes !== []) {
            return false;
        }

        return ClassesMustNotBeSubclassRule::errorFoundFor($node);
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
