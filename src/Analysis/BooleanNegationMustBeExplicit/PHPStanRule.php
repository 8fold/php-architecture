<?php

declare(strict_types=1);

namespace Eightfold\Analysis\BooleanNegationMustBeExplicit;

use Eightfold\Analysis\MessageCompiler;
use PhpParser\Node;
use PhpParser\Node\Expr\BooleanNot;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<BooleanNot>
 */
final class PHPStanRule implements Rule
{
    public function getNodeType(): string
    {
        return BooleanNot::class;
    }

    /**
     * @param BooleanNot $node
     * @return list<IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if ($scope->getType($node->expr)->isBoolean()->yes() === false) {
            return [];
        }

        return [
            RuleErrorBuilder::message(self::compiler()->compile('message'))->tip(
                self::compiler()->compile('help')
            )->line(
                $node->getStartLine()
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
