<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPStan;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

use Eightfold\PHPStan\Rules\CallablesMustBeTyped;

use Eightfold\Tests\PHPStan\Fixtures\CallablesMustBeTypedNoncompliant;

use Eightfold\Attributes\Classes\Subclass;

/**
 * @extends RuleTestCase<CallablesMustBeTyped>
 */
#[Subclass('PHPUnit and PHPStan\Testing require exntension.')]
final class CallablesMustBeTypedTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new CallablesMustBeTyped();
    }

    #[Test]
    #[Group('analysis')]
    public function noncompliant_fails(): void
    {
        $noncompliant = CallablesMustBeTypedNoncompliant::class;

        parent::analyse(
            [__DIR__ . '/Fixtures/CallablesMustBeTypedNoncompliant.php'],
            [
                [
                    CallablesMustBeTyped::message('processData'),
                    8,
                    CallablesMustBeTyped::tip()
                ],
                [
                    CallablesMustBeTyped::message('unnamed callable'),
                    11,
                    CallablesMustBeTyped::tip()
                ]
            ]
        );
    }

    #[Test]
    #[Group('analysis')]
    #[Group('isolate')]
    public function compliant_succeeds(): void
    {
        parent::analyse(
            [__DIR__ . '/Fixtures/CallablesMustBeTypedCompliant.php'],
            []
        );
    }
}
