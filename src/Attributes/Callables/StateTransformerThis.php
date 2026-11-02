<?php

declare(strict_types=1);

namespace Eightfold\Attributes\Callables;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class StateTransformerThis
{
    public static function behavior(): string
    {
        // phpcs:disable
        return <<<'behavior'
        Mutates internal state on `$this` in place and returns `$this` for fluent method chaining.
        behavior;
        // phpcs:enable
    }

    public static function definingInvariants(): string
    {
        // phpcs:disable
        return <<<'qualities'
        1. In-Place Mutation: Mutates only the internal state of the current instance.
        2. Self-Referential Yield: Returns the same `$this` instance, not a clone.
        3. Non-Static Instance Method: Enables fluent chaining on a single mutable instance.
        qualities;
        // phpcs:enable
    }

    public static function recommendedAlternatives(): string
    {
        // phpcs:disable
        return '';
        // phpcs:enable
    }

    public static function example(): string
    {
        // phpcs:disable
        return <<<'example'
        #[\Eightfold\Attributes\Callables\StateTransformerThis]
        public function setAmount(float $newAmount): self
        {
            $this->amount = $newAmount;
            return $this;
        }
        example;
        // phpcs:enable
    }
}
