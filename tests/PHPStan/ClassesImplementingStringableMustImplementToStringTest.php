<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPStan;

use Eightfold\Analysis\ClassesImplementingStringableMustImplementToString\PHPStanRule;
use Eightfold\Attributes\Classes\Subclass;
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

    private const FIXTURE_PREFIX = 'ClassesImplementingStringableMustImplementToString';

    protected function getRule(): PHPStanRule
    {
        return new PHPStanRule();
    }

    #[Test]
    #[Group('analysis')]
    public function reports_each_directly_stringable_class_once(): void
    {
        self::loadFixtureSymbols(self::FIXTURE_PREFIX . 'Noncompliant.php');

        $errors = [];
        foreach ([17, 25, 33, 41, 49, 57, 61, 71, 83, 94] as $line) {
            $errors[] = [self::MESSAGE, $line, self::TIP];
        }

        parent::analyse(
            [
                dirname(__DIR__)
                . '/PHPCS/Fixtures/' . self::FIXTURE_PREFIX . 'Noncompliant.php',
            ],
            $errors
        );
    }

    #[Test]
    #[Group('analysis')]
    public function accepts_suite_contract_implementations_and_non_targets(): void
    {
        self::loadFixtureSymbols(self::FIXTURE_PREFIX . 'Compliant.php');

        parent::analyse(
            [
                dirname(__DIR__)
                . '/PHPCS/Fixtures/' . self::FIXTURE_PREFIX . 'Compliant.php',
            ],
            []
        );
    }

    private static function loadFixtureSymbols(string $name): void
    {
        $fixtures = dirname(__DIR__) . '/PHPCS/Fixtures/';
        require_once $fixtures . $name;
    }
}
