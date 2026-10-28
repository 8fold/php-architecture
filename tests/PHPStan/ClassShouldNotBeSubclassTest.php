<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPStan;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

use Eightfold\PHPStan\Rules\ClassShouldNotBeSubclass;

use Eightfold\Tests\PHPStan\Fixtures\ClassMustNotBeSubclassNoncompliant;

use Eightfold\Attributes\Subclass;

/**
 * @extends RuleTestCase<ClassShouldNotBeSubclass>
 */
#[Subclass('PHPUnit and PHPStan\Testing require exntension.')]
final class ClassShouldNotBeSubclassTest extends RuletestCase
{
    protected function getRule(): Rule
    {
        return new ClassShouldNotBeSubclass();
    }

    #[Test]
    #[Group('analysis')]
    public function noncompliant_fails(): void
    {
        $noncompliant = ClassMustNotBeSubclassNoncompliant::class;

        parent::analyse(
            [__DIR__ . '/Fixtures/ClassMustNotBeSubclassNoncompliant.php'],
            [
                [
                    ClassShouldNotBeSubclass::message($noncompliant),
                    7,
                    ClassShouldNotBeSubclass::tip($noncompliant, false)
                ]
            ]
        );
    }

    #[Test]
    #[Group('analysis')]
    public function compliant_using_attributes_succeeds(): void
    {
        parent::analyse(
            [__DIR__ . '/Fixtures/ClassShouldNotBeSubclassCompliantUsingAttribute.php'],
            []
        );
    }

    #[Test]
    #[Group('analysis')]
    public function compliant_succeeds(): void
    {
        parent::analyse(
            [__DIR__ . '/Fixtures/ClassMustNotBeSubclassCompliant.php'],
            []
        );
    }
}
