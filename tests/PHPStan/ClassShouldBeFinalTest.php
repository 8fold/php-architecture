<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPStan;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

use Eightfold\PHPStan\Rules\ClassShouldBeFinal;

use Eightfold\Tests\PHPStan\Fixtures\ClassShouldBeFinalNoncompliant;
use Eightfold\Tests\PHPStan\Fixtures\ClassShouldNotBeSubclassCompliantUsingAttribute;

use Eightfold\Attributes\Classes\Subclass;

/**
 * @extends RuleTestCase<ClassShouldBeFinal>
 */
#[Subclass('PHPUnit and PHPStan\Testing require exntension.')]
final class ClassShouldBeFinalTest extends RuletestCase
{
    protected function getRule(): Rule
    {
        return new ClassShouldBeFinal();
    }

    #[Test]
    #[Group('analysis')]
    public function noncompliant_fails(): void
    {
        $noncompliant = ClassShouldBeFinalNoncompliant::class;

        parent::analyse(
            [__DIR__ . '/Fixtures/ClassShouldBeFinalNoncompliant.php'],
            [
                [
                    ClassShouldBeFinal::message($noncompliant),
                    5,
                    ClassShouldBeFinal::tip($noncompliant, false)
                ]
            ]
        );
    }

    #[Test]
    #[Group('analysis')]
    public function compliant_using_attributes_succeeds(): void
    {
        parent::analyse(
            [__DIR__ . '/Fixtures/ClassShouldBeFinalCompliantUsingAttribute.php'],
            []
        );
    }

    #[Test]
    #[Group('analysis')]
    public function subclass_attribute_does_not_suppress_finality(): void
    {
        $noncompliant = ClassShouldNotBeSubclassCompliantUsingAttribute::class;

        parent::analyse(
            [__DIR__ . '/Fixtures/ClassShouldNotBeSubclassCompliantUsingAttribute.php'],
            [
                [
                    ClassShouldBeFinal::message($noncompliant),
                    7,
                    ClassShouldBeFinal::tip($noncompliant, false)
                ]
            ]
        );
    }

    #[Test]
    #[Group('analysis')]
    public function finality_tip_recommends_final_before_the_attribute(): void
    {
        $tip = ClassShouldBeFinal::tip('Example');

        parent::assertStringContainsString('Change declaration to `final class Example`', $tip);
        parent::assertStringContainsString('Eightfold\Attributes\Classes\BaseClass', $tip);
    }

    #[Test]
    #[Group('analysis')]
    public function compliant_succeeds(): void
    {
        parent::analyse(
            [__DIR__ . '/Fixtures/ClassMustBeFinalCompliant.php'],
            []
        );
    }
}
