<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPStan;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

use Eightfold\Analysis\ClassesMustNotBeSubclass\PHPStanRule as ClassesMustNotBeSubclass;

use Eightfold\Tests\PHPStan\Fixtures\{
    ClassesMustNotBeSubclass\Noncompliant as ClassesMustNotBeSubclassNoncompliant,
    ClassesShouldNotBeSubclass\Compliant as ClassesShouldNotBeSubclassCompliant,
};

use Eightfold\Attributes\Classes\Subclass;

/**
 * @extends RuleTestCase<ClassesMustNotBeSubclass>
 */
#[Subclass('PHPUnit and PHPStan\Testing require exntension.')]
final class ClassesMustNotBeSubclassTest extends RuletestCase
{
    protected function getRule(): Rule
    {
        return new ClassesMustNotBeSubclass();
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
                    ClassesMustNotBeSubclass::message($noncompliant),
                    7,
                    ClassesMustNotBeSubclass::tip($noncompliant, false)
                ]
            ]
        );
    }

    #[Test]
    #[Group('analysis')]
    public function subclass_attribute_does_not_suppress_a_mandatory_rule(): void
    {
        $noncompliant = ClassesShouldNotBeSubclassCompliant::class;

        parent::analyse(
            [__DIR__ . '/Fixtures/ClassesShouldNotBeSubclass/Compliant.php'],
            [
                [
                    ClassesMustNotBeSubclass::message($noncompliant),
                    7,
                    ClassesMustNotBeSubclass::tip($noncompliant, false)
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
