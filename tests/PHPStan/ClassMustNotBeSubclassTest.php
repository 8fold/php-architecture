<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPStan;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

use Eightfold\PHPStan\Rules\ClassMustNotBeSubclass;

use Eightfold\Tests\PHPStan\Fixtures\ClassMustNotBeSubclassNoncompliant;

use Eightfold\Attributes\Subclass;

/**
 * @extends RuleTestCase<ClassMustNotBeSubclass>
 */
#[Subclass('PHPUnit and PHPStan\Testing require exntension.')]
final class ClassMustNotBeSubclassTest extends RuletestCase
{
    protected function getRule(): Rule
    {
        return new ClassMustNotBeSubclass();
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
                    ClassMustNotBeSubclass::message($noncompliant),
                    7,
                    ClassMustNotBeSubclass::tip($noncompliant, false)
                ]
            ]
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
