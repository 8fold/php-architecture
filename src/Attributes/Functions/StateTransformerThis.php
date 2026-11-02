<?php

declare(strict_types=1);

namespace Eightfold\Attributes\Functions;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class StateTransformerThis
{
    public static function behavior(): string
    {
        return <<<'behavior'
        Mutates internal state directly on `$this` in place and returns `$this` to enable fluent
        method chaining.
        behavior;
    }

    public static function definingInvariants(): string
    {
        return <<<'qualities'
        1. In-Place Mutation: Directly mutates internal state properties on the current instance
           ($this).
        2. Self-Referential Yield (return $this): Always returns the identical object instance
           reference ($this) to facilitate method chaining ($result === $this).
        3. Method Chaining Ergonomics: Combines state mutation with callability, allowing
           multi-step configuration pipelines on a single mutable instance.
        qualities;
    }

    public static function recommendedAlternatives(): string
    {
        return '';
    }

    public static function example(): string
    {
        return <<<'example'
        #[\Eightfold\Attributes\Functions\StateTransformerThis]
        public function setAmount(float $newAmount): self
        {
            $this->amount = $newAmount;
            return $this;
        }
        example;
    }
}
