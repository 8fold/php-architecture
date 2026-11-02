<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPStan;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

use Eightfold\Analysis\ClassesMustBeFinal\PHPStanRule as ClassesMustBeFinal;
use Eightfold\Analysis\ClassesShouldBeFinal\PHPStanRule as ClassesShouldBeFinal;
use Eightfold\Attributes\Classes\BaseClass;

use Eightfold\Tests\PHPStan\Fixtures\{
    ClassesShouldBeFinal\Noncompliant as ClassesShouldBeFinalNoncompliant,
    ClassesShouldNotBeSubclass\Compliant as ClassesShouldNotBeSubclassCompliant,
};

use Eightfold\Attributes\Classes\Subclass;

/**
 * @extends RuleTestCase<ClassesShouldBeFinal>
 */
#[Subclass('PHPUnit and PHPStan\Testing require exntension.')]
final class ClassesShouldBeFinalTest extends RuletestCase
{
    protected function getRule(): Rule
    {
        return new ClassesShouldBeFinal();
    }

    #[Test]
    #[Group('analysis')]
    public function noncompliant_fails(): void
    {
        $noncompliant = ClassesShouldBeFinalNoncompliant::class;

        parent::analyse(
            [__DIR__ . '/Fixtures/ClassesShouldBeFinal/Noncompliant.php'],
            [
                [
                    ClassesShouldBeFinal::message($noncompliant),
                    5,
                    ClassesShouldBeFinal::tip($noncompliant, false)
                ]
            ]
        );
    }

    #[Test]
    #[Group('analysis')]
    public function compliant_using_attributes_succeeds(): void
    {
        parent::analyse(
            [__DIR__ . '/Fixtures/ClassesShouldBeFinal/Compliant.php'],
            []
        );
    }

    #[Test]
    #[Group('analysis')]
    public function subclass_attribute_does_not_suppress_finality(): void
    {
        $noncompliant = ClassesShouldNotBeSubclassCompliant::class;

        parent::analyse(
            [__DIR__ . '/Fixtures/ClassesShouldNotBeSubclass/Compliant.php'],
            [
                [
                    ClassesShouldBeFinal::message($noncompliant),
                    7,
                    ClassesShouldBeFinal::tip($noncompliant, false)
                ]
            ]
        );
    }

    #[Test]
    #[Group('analysis')]
    public function finality_tip_recommends_final_before_the_attribute(): void
    {
        $tip = ClassesShouldBeFinal::tip('Example');

        parent::assertStringContainsString('Change declaration to `final class Example`', $tip);
        parent::assertStringContainsString('Eightfold\Attributes\Classes\BaseClass', $tip);
    }

    #[Test]
    #[Group('analysis')]
    public function compliant_succeeds(): void
    {
        parent::analyse(
            [__DIR__ . '/Fixtures/ClassesMustBeFinal/Compliant.php'],
            []
        );
    }

    #[Test]
    #[Group('analysis')]
    public function tip_reuses_must_guidance_and_adds_the_opt_out_attribute(): void
    {
        $className = 'Example\\Widget';
        $tip = ClassesShouldBeFinal::tip($className);
        $mustTip = ClassesMustBeFinal::tip($className, true);
        $expectedTip = $mustTip
            . "\nor, decorate the class with the `"
            . BaseClass::class
            . '` attribute.';
        parent::assertSame(
            $expectedTip,
            $tip
        );
    }
}
