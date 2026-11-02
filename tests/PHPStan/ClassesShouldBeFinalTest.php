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

use Eightfold\Tests\PHPStan\Fixtures\ClassesShouldBeFinalNoncompliant;

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
            [__DIR__ . '/Fixtures/ClassesShouldBeFinalNoncompliant.php'],
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
            [__DIR__ . '/Fixtures/ClassesShouldBeFinalCompliantUsingAttribute.php'],
            []
        );
    }

    #[Test]
    #[Group('analysis')]
    public function compliant_succeeds(): void
    {
        parent::analyse(
            [__DIR__ . '/Fixtures/ClassesMustBeFinalCompliant.php'],
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
