<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPStan;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

use Eightfold\PHPStan\Rules\ClassShouldBeFinal;

use Eightfold\Tests\PHPStan\Fixtures\ClassShouldBeFinalNonCompliant;

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
        parent::analyse(
            [__DIR__ . '/Fixtures/ClassShouldBeFinalNoncompliant.php'],
            [
                [ClassShouldBeFinal::message(), 5, ClassShouldBeFinal::tip()]
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
}
