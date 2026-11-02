<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPStan;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

use Eightfold\Analysis\ClassesShouldNotBeSubclass\PHPStanRule as ClassesShouldNotBeSubclass;

use Eightfold\Tests\PHPStan\Fixtures\ClassesMustNotBeSubclassNoncompliant;

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
            [__DIR__ . '/Fixtures/ClassesMustNotBeSubclassNoncompliant.php'],
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
            [__DIR__ . '/Fixtures/ClassesShouldNotBeSubclassCompliantUsingAttribute.php'],
            []
        );
    }

    #[Test]
    #[Group('analysis')]
    public function compliant_succeeds(): void
    {
        parent::analyse(
            [__DIR__ . '/Fixtures/ClassesMustNotBeSubclassCompliant.php'],
            []
        );
    }
}
