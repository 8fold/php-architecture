<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPCS\Fixtures\InstanceMethodsMustNotBeAccessedViaThis;

use Closure;

final class Compliant
{
    private string $name = 'Ada';

    private ?self $other = null;

    public function __construct(private Closure $callback)
    {
    }

    /** No method-access diagnostics are expected. */
    public function exercise(self $other): string
    {
        $this->name = trim($this->name);

        $property = 'name';
        $name = $this->$property;
        $this->{$property} = $name;
        $name = $this?->name;
        $name = ($this)->name;

        ($this->callback)();
        $callbackProperty = 'callback';
        ($this->{$callbackProperty})();

        $other->instanceMethod();
        $this->other?->instanceMethod();
        self::instanceMethod();
        self::staticMethod();

        $literal = '$this->instanceMethod()';

        return "{$this->name}: {$literal}";
    }

    private function instanceMethod(): void
    {
    }

    private static function staticMethod(): void
    {
    }
}
