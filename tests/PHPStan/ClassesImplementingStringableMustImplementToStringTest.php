<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPStan;

use Eightfold\Analysis\ClassesImplementingStringableMustImplementToString\PHPStanRule;
use Eightfold\Attributes\Classes\Subclass;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\MutatingScope;
use PHPStan\Rules\NonIgnorableRuleError;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends RuleTestCase<PHPStanRule>
 */
#[Subclass(rationale: 'PHPStan rule tests require RuleTestCase.')]
final class ClassesImplementingStringableMustImplementToStringTest extends RuleTestCase
{
    private const MESSAGE =
        'Classes implementing Stringable must implement Eightfold\\Interfaces\\ToString.';

    private const TIP =
        'Implement Eightfold\\Interfaces\\ToString and its toString(): string method.';

    protected function getRule(): PHPStanRule
    {
        return new PHPStanRule();
    }

    #[Test]
    #[Group('analysis')]
    public function reports_each_directly_stringable_class_once(): void
    {
        self::loadFixtureSymbols('ClassesImplementingStringableMustImplementToStringNoncompliant.php');

        $errors = [];
        foreach ([17, 25, 33, 41, 49, 57, 61, 71, 83, 94] as $line) {
            $errors[] = [self::MESSAGE, $line, self::TIP];
        }

        parent::analyse(
            [
                dirname(__DIR__)
                . '/PHPCS/Fixtures/ClassesImplementingStringableMustImplementToStringContract.php',
                dirname(__DIR__)
                . '/PHPCS/Fixtures/ClassesImplementingStringableMustImplementToStringNoncompliant.php',
            ],
            $errors
        );
    }

    #[Test]
    #[Group('analysis')]
    public function accepts_suite_contract_implementations_and_non_targets(): void
    {
        self::loadFixtureSymbols('ClassesImplementingStringableMustImplementToStringCompliant.php');

        parent::analyse(
            [
                dirname(__DIR__)
                . '/PHPCS/Fixtures/ClassesImplementingStringableMustImplementToStringContract.php',
                dirname(__DIR__)
                . '/PHPCS/Fixtures/ClassesImplementingStringableMustImplementToStringCompliant.php',
            ],
            []
        );
    }

    #[Test]
    #[Group('analysis')]
    public function reports_a_non_ignorable_must_with_a_stable_identifier(): void
    {
        $node = new Class_(new Identifier('Example'));
        $scope = parent::createStub(MutatingScope::class);
        $errors = self::getRule()->processNode($node, $scope);

        parent::assertCount(1, $errors);
        parent::assertSame(
            'eightfold.classesImplementingStringableMustImplementToString',
            $errors[0]->getIdentifier()
        );
        parent::assertInstanceOf(NonIgnorableRuleError::class, $errors[0]);
    }

    private static function loadFixtureSymbols(string $name): void
    {
        $fixtures = dirname(__DIR__) . '/PHPCS/Fixtures/';
        require_once $fixtures
            . 'ClassesImplementingStringableMustImplementToStringContract.php';
        require_once $fixtures . $name;
    }
}
