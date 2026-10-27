<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPStan;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

use Eightfold\PHPStan\Rules\ClassMustBeFinal;

use Eightfold\Tests\PHPStan\Fixtures\ClassMustBeFinalNonCompliant;

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
        parent::analyse(
            [__DIR__ . '/Fixtures/ClassMustBeFinalNoncompliant.php'],
            [
                [ClassMustBeFinal::message(), 5, ClassMustBeFinal::tip()]
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
