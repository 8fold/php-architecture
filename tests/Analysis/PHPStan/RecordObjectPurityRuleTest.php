<?php

declare(strict_types=1);

namespace Eightfold\Architecture\Tests\Analysis;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

use Eightfold\Analysis\PHPStan\RecordObjectPurityRule;

/**
 * Pins the expected errors against fixtures; the project sources passing
 * composer production are the acceptance fixture (every #[RecordObject] class
 * is pure by construction).
 *
 * @extends RuleTestCase<RecordObjectPurityRule>
 */
final class RecordObjectPurityRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new RecordObjectPurityRule();
    }

    #[Test]
    #[Group('analysis')]
    public function it_flags_every_purity_violation(): void
    {
        $impure = '#[RecordObject] Eightfold\Analysis\PHPStan\Fixtures\ImpureRecordObject';

        parent::analyse(
            [__DIR__ . '/../../../src/Analysis/PHPStan/Fixtures/ImpureRecordObject.php'],
            [
                [
                    $impure . ' declares mutable $mutable; record'
                    . ' properties are promoted readonly or get-only'
                    . ' property hooks.',
                    15,
                ],
                [
                    $impure . ' declares static $shared; record objects'
                    . ' hold row values, not shared state.',
                    17,
                ],
                [
                    $impure . ' defines a set hook on $id; record'
                    . ' property hooks are get-only views — mutate by'
                    . ' constructing a new instance.',
                    21,
                ],
                [
                    $impure . ' promotes $token without readonly; record'
                    . ' properties are readonly values.',
                    25,
                ],
                [
                    $impure . ' declares static init(); record objects'
                    . ' keep strict purity — no static initializers, call'
                    . ' the public constructor directly'
                    . ' (docs/data-objects.md).',
                    29,
                ],
                [
                    $impure . ' declares rename(); record objects are'
                    . ' promoted properties and property hooks'
                    . ' only — behavior belongs elsewhere'
                    . ' (docs/data-objects.md).',
                    34,
                ],
            ]
        );
    }

    #[Test]
    #[Group('analysis')]
    public function it_accepts_a_pure_record(): void
    {
        parent::analyse(
            [__DIR__ . '/../../../src/Analysis/PHPStan/Fixtures/PureRecordObject.php'],
            []
        );
    }
}
