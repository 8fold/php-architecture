<?php

declare(strict_types=1);

namespace Eightfold\Attributes\Callables;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class NamedConstructor
{
    public static function behavior(): string
    {
        // phpcs:disable
        return <<<behavior
        Encapsulates instantiation logic, parameter normalization, or driver selection.
        behavior;
        // phpcs:enable
    }

    public static function definingInvariants(): string
    {
        // phpcs:disable
        return <<<qualities
        1. Construction Abstraction: Decouples instantiation details from direct constructor invocation.
        2. Expressive Entry Point: Conveys domain intent via descriptive names (e.g., `fromCents()`, `fromArray()`).
        3. Instance Return Guarantee: Yields a new, fully initialized `self` or `static` instance.
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
        #[\Eightfold\Attributes\Callables\NamedConstructor]
        public static function fromCents(int $cents, string $currency = 'USD'): self
        {
            return new self($cents / 100.0, $currency);
        }
        example;
        // phpcs:enable
    }
}
