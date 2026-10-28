<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPStan;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

use Eightfold\PHPStan\Rules\ClassMustBeFinal;

use Eightfold\Tests\PHPStan\Fixtures\ClassMustBeFinalNoncompliant;

use Eightfold\Attributes\Subclass;

/**
 * @extends RuleTestCase<ClassMustBeFinal>
 */
#[Subclass('PHPUnit and PHPStan\Testing require exntension.')]
final class ClassMustBeFinalTest extends RuletestCase
{
    protected function getRule(): Rule
    {
        return new ClassMustBeFinal();
    }

    #[Test]
    #[Group('analysis')]
    public function noncompliant_fails(): void
    {
        $noncompliant = ClassMustBeFinalNoncompliant::class;

        parent::analyse(
            [__DIR__ . '/Fixtures/ClassMustBeFinalNoncompliant.php'],
            [
                [
                    ClassMustBeFinal::message($noncompliant),
                    5,
                    ClassMustBeFinal::tip($noncompliant, false)
                ]
            ]
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
}
