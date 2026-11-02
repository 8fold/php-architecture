<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPStan;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

use Eightfold\Analysis\ClassesMustBeFinal\PHPStanRule as ClassesMustBeFinal;

use Eightfold\Tests\PHPStan\Fixtures\ClassesMustBeFinalNoncompliant;

use Eightfold\Attributes\Classes\Subclass;

/**
 * @extends RuleTestCase<ClassesMustBeFinal>
 */
#[Subclass('PHPUnit and PHPStan\Testing require exntension.')]
final class ClassesMustBeFinalTest extends RuletestCase
{
    protected function getRule(): Rule
    {
        return new ClassesMustBeFinal();
    }

    #[Test]
    #[Group('analysis')]
    public function noncompliant_fails(): void
    {
        $noncompliant = ClassesMustBeFinalNoncompliant::class;

        parent::analyse(
            [__DIR__ . '/Fixtures/ClassesMustBeFinalNoncompliant.php'],
            [
                [
                    ClassesMustBeFinal::message($noncompliant),
                    5,
                    ClassesMustBeFinal::tip($noncompliant, false)
                ]
            ]
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
}
