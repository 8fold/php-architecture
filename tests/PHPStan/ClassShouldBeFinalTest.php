<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPStan;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

use Eightfold\Analysis\ClassMustBeFinal\PHPStanRule as ClassMustBeFinal;
use Eightfold\Analysis\ClassShouldBeFinal\PHPStanRule as ClassShouldBeFinal;
use Eightfold\Attributes\Classes\BaseClass;

use Eightfold\Tests\PHPStan\Fixtures\ClassShouldBeFinalNoncompliant;

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
    public function compliant_succeeds(): void
    {
        parent::analyse(
            [__DIR__ . '/Fixtures/ClassMustBeFinalCompliant.php'],
            []
        );
    }

    #[Test]
    #[Group('analysis')]
    public function tip_reuses_must_guidance_and_adds_the_opt_out_attribute(): void
    {
        $className = 'Example\\Widget';
        $tip = ClassShouldBeFinal::tip($className);
        $mustTip = ClassMustBeFinal::tip($className, true);
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
