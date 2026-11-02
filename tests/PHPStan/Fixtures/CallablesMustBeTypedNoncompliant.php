<?php

namespace Eightfold\Tests\PHPStan\Fixtures;

class CallablesMustBeTypedNoncompliant
{
    // Untyped instance method
    public function processData(): void
    {
        // Untyped closure
        $untypedClosure = function (int $a): int {
            return $a + 1;
        };

        $untypedClosure(10);
    }
}
