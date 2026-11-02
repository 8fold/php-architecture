<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPStan;

use Eightfold\Attributes\Classes\Subclass;
use Eightfold\PHPStan\Rules\BooleanNegationMustBeExplicit;
use Eightfold\Tests\PHPStan\Fixtures\BooleanNegationMustBeExplicitCompliant;
use Eightfold\Tests\PHPStan\Fixtures\BooleanNegationMustBeExplicitNoncompliant;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Variable;
use PHPStan\Analyser\MutatingScope;
use PHPStan\Rules\NonIgnorableRuleError;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\BooleanType;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends RuleTestCase<BooleanNegationMustBeExplicit>
 */
#[Subclass(rationale: 'PHPStan rule tests require RuleTestCase.')]
final class BooleanNegationMustBeExplicitTest extends RuleTestCase
{
    private const MESSAGE =
        'Boolean negation must use an explicit comparison or a positive condition.';

    private const TIP =
        'For a boolean operand, replace !expression with expression === false,'
        . ' preserving grouping and short-circuiting.';

    protected function getRule(): BooleanNegationMustBeExplicit
    {
        return new BooleanNegationMustBeExplicit();
    }

    #[Test]
    #[Group('analysis')]
    public function reports_every_boolean_negation_at_its_source_line(): void
    {
        $errors = [];
        foreach ([11, 16, 24, 30, 36, 42, 47, 52, 57, 57] as $line) {
            $errors[] = [self::MESSAGE, $line, self::TIP];
        }

        parent::analyse(
            [__DIR__ . '/Fixtures/BooleanNegationMustBeExplicitNoncompliant.php'],
            $errors
        );
    }

    #[Test]
    #[Group('analysis')]
    public function accepts_explicit_comparisons_and_positive_conditions(): void
    {
        parent::analyse(
            [__DIR__ . '/Fixtures/BooleanNegationMustBeExplicitCompliant.php'],
            []
        );
    }

    #[Test]
    #[Group('analysis')]
    public function leaves_non_boolean_truthiness_for_intent_review(): void
    {
        parent::analyse(
            [__DIR__ . '/Fixtures/BooleanNegationMustBeExplicitNonBoolean.php'],
            []
        );
    }

    #[Test]
    #[Group('analysis')]
    public function reports_a_non_ignorable_must_with_a_stable_identifier(): void
    {
        $node = new BooleanNot(new Variable('flag'));
        $scope = parent::createStub(MutatingScope::class);
        $scope->method('getType')->willReturn(new BooleanType());

        $errors = self::getRule()->processNode($node, $scope);

        parent::assertCount(1, $errors);
        parent::assertSame('eightfold.booleanNegationMustBeExplicit', $errors[0]->getIdentifier());
        parent::assertInstanceOf(NonIgnorableRuleError::class, $errors[0]);
    }

    /** @return array<string, array{bool}> */
    public static function booleans(): array
    {
        return [
            'false' => [false],
            'true' => [true],
        ];
    }

    #[Test]
    #[Group('analysis')]
    #[DataProvider('booleans')]
    public function explicit_comparisons_preserve_boolean_results(bool $flag): void
    {
        parent::assertSame(
            BooleanNegationMustBeExplicitNoncompliant::variable($flag),
            BooleanNegationMustBeExplicitCompliant::variable($flag)
        );
        parent::assertSame(
            BooleanNegationMustBeExplicitNoncompliant::predicate($flag),
            BooleanNegationMustBeExplicitCompliant::predicate($flag)
        );
        parent::assertSame(
            BooleanNegationMustBeExplicitNoncompliant::normalized($flag),
            BooleanNegationMustBeExplicitCompliant::normalized($flag)
        );
        foreach ([false, true] as $right) {
            parent::assertSame(
                BooleanNegationMustBeExplicitNoncompliant::compound($flag, $right),
                BooleanNegationMustBeExplicitCompliant::compound($flag, $right)
            );
        }
    }
}
