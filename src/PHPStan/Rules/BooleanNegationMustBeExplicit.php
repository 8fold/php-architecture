<?php

declare(strict_types=1);

namespace Eightfold\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\BooleanNot;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<BooleanNot>
 */
final class BooleanNegationMustBeExplicit implements Rule
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
            RuleErrorBuilder::message(
                'Boolean negation must use an explicit comparison or a positive condition.'
            )->tip(
                'For a boolean operand, replace !expression with expression === false,'
                . ' preserving grouping and short-circuiting.'
            )->line(
                $node->getStartLine()
            )->identifier(
                'eightfold.booleanNegationMustBeExplicit'
            )->nonIgnorable()->build(),
        ];
    }
}
