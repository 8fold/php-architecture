<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPStan;

use Eightfold\Analysis\ClassesImplementingStringableMustImplementToString\PHPStanRule;
use Eightfold\Attributes\Classes\Subclass;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
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

    private bool $includeIndirect = false;

    protected function getRule(): PHPStanRule
    {
        return new PHPStanRule($this->includeIndirect);
    }

    /** @return array<string, array{bool}> */
    public static function ruleModes(): array
    {
        return [
            'direct' => [false],
            'indirect' => [true],
        ];
    }

    #[Test]
    #[Group('analysis')]
    #[DataProvider('ruleModes')]
    public function reports_each_directly_stringable_class_once(bool $includeIndirect): void
    {
        $this->includeIndirect = $includeIndirect;
        self::loadFixtureSymbols(self::FIXTURE_PREFIX . 'Noncompliant.php');

        $errors = [];
        foreach ([17, 25, 33, 41, 49, 57, 61, 71, 83, 94] as $line) {
            $errors[] = [self::MESSAGE, $line, self::TIP];
        }

        parent::analyse(
            [
                __DIR__ . '/Fixtures/' . self::FIXTURE_PREFIX . 'Noncompliant.php',
            ],
            $errors
        );
    }

    #[Test]
    #[Group('analysis')]
    #[DataProvider('ruleModes')]
    public function accepts_direct_suite_contracts_and_non_targets(bool $includeIndirect): void
    {
        $this->includeIndirect = $includeIndirect;
        self::loadFixtureSymbols(self::FIXTURE_PREFIX . 'Compliant.php');

        parent::analyse(
            [
                __DIR__ . '/Fixtures/' . self::FIXTURE_PREFIX . 'Compliant.php',
            ],
            []
        );
    }

    /** @return array<string, array{string}> */
    public static function indirectCompliantFixtures(): array
    {
        return [
            'same file' => [self::FIXTURE_PREFIX . 'IndirectCompliant.php'],
            'cross file' => [self::FIXTURE_PREFIX . 'IndirectCrossFileCompliant.php'],
        ];
    }

    #[Test]
    #[Group('analysis')]
    #[DataProvider('indirectCompliantFixtures')]
    public function accepts_inherited_and_indirect_suite_contracts(string $fixture): void
    {
        $this->includeIndirect = true;
        self::loadFixtureSymbols($fixture);

        parent::analyse(
            [__DIR__ . '/Fixtures/' . $fixture],
            []
        );
    }

    #[Test]
    #[Group('analysis')]
    public function reports_trait_provided_stringability_in_a_parent(): void
    {
        $this->includeIndirect = true;
        $fixture = self::FIXTURE_PREFIX . 'IndirectParentNoncompliant.php';
        self::loadFixtureSymbols($fixture);

        parent::analyse(
            [__DIR__ . '/Fixtures/' . $fixture],
            [[self::MESSAGE, 14, self::TIP]]
        );
    }

    #[Test]
    #[Group('analysis')]
    public function reports_each_indirectly_stringable_class_without_the_suite_contract(): void
    {
        $this->includeIndirect = true;
        $fixture = self::FIXTURE_PREFIX . 'IndirectNoncompliant.php';
        self::loadFixtureSymbols($fixture);

        $errors = [];
        foreach ([17, 22, 31, 35, 40, 45, 60] as $line) {
            $errors[] = [self::MESSAGE, $line, self::TIP];
        }

        parent::analyse(
            [__DIR__ . '/Fixtures/' . $fixture],
            $errors
        );
    }

    private static function loadFixtureSymbols(string $name): void
    {
        $fixtures = __DIR__ . '/Fixtures/';
        require_once $fixtures . $name;
    }
}
