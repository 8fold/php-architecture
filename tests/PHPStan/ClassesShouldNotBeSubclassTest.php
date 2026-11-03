<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPStan;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

use Eightfold\Analysis\ClassesShouldNotBeSubclass\PHPStanRule as ClassesShouldNotBeSubclass;

use Eightfold\Tests\PHPStan\Fixtures\{
    ClassesMustNotBeSubclass\Noncompliant as ClassesMustNotBeSubclassNoncompliant,
    ClassesShouldBeFinal\Compliant as ClassesShouldBeFinalCompliant,
};

use Eightfold\Attributes\Classes\Subclass;

/**
 * @extends RuleTestCase<ClassesShouldNotBeSubclass>
 */
#[Subclass('PHPUnit and PHPStan\Testing require exntension.')]
final class ClassesShouldNotBeSubclassTest extends RuletestCase
{
    protected function getRule(): Rule
    {
        return new ClassesShouldNotBeSubclass();
    }

    #[Test]
    #[Group('analysis')]
    public function noncompliant_fails(): void
    {
        $noncompliant = ClassesMustNotBeSubclassNoncompliant::class;

        parent::analyse(
            [__DIR__ . '/Fixtures/ClassesMustNotBeSubclass/Noncompliant.php'],
            [
                [
                    ClassesShouldNotBeSubclass::message($noncompliant),
                    7,
                    ClassesShouldNotBeSubclass::tip($noncompliant, false)
                ]
            ]
        );
    }

    #[Test]
    #[Group('analysis')]
    public function compliant_using_attributes_succeeds(): void
    {
        parent::analyse(
            [__DIR__ . '/Fixtures/ClassesShouldNotBeSubclass/Compliant.php'],
            []
        );
    }

    #[Test]
    #[Group('analysis')]
    public function base_class_attribute_does_not_suppress_subclass_diagnostics(): void
    {
        $noncompliant = ClassesShouldBeFinalCompliant::class;

        parent::analyse(
            [__DIR__ . '/Fixtures/ClassesShouldBeFinal/Compliant.php'],
            [
                [
                    ClassesShouldNotBeSubclass::message($noncompliant),
                    7,
                    ClassesShouldNotBeSubclass::tip($noncompliant, false)
                ]
            ]
        );
    }

    #[Test]
    #[Group('analysis')]
    public function compliant_succeeds(): void
    {
        parent::analyse(
            [__DIR__ . '/Fixtures/ClassesMustNotBeSubclass/Compliant.php'],
            []
        );
    }
}
