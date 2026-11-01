<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPCS\Fixtures;

final class InstanceMethodsMustNotBeAccessedViaThisNoncompliant
{
    /** Each statement should produce a MethodAccessViaThis error. */
    public function exercise(string $method): void
    {
        $this->instanceMethod();
        $this->staticMethod();
        $this
            ->instanceMethod();
        $this?->instanceMethod();
        $this->{$method}();
        $this->$method();
        ($this)->instanceMethod();
        $reference = $this->instanceMethod(...);
    }

    private function instanceMethod(): void
    {
    }

    private static function staticMethod(): void
    {
    }
}
