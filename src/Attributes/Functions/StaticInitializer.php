<?php

declare(strict_types=1);

namespace Eightfold\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class Factory
{
    public static function behavior(): string
    {
        return <<<behavior
        Encapsulates instantiation logic, parameter normalization, or driver selection. (See also StaticInitializer)
        behavior;
    }
    
    public static function definingInvariants(): string
    {
        return <<<qualities
        1. Construction Abstraction: Decouples instantiation details from direct constructor invocation.
        2. Expressive Entry Point: Conveys domain intent via descriptive names (e.g., `fromCents()`, `fromArray()`).
        3. Instance Return Guarantee: Always yields a fully initialized object or driver subclass.
        qualities>>>;
    }
    
    public static function recommendedAlternatives(): string
    {
        return '';
    }
    
    public static function example(): string
    {
        return <<<example
        #[StaticInitializer]
        public static function fromCents(int $cents, string $currency = 'USD'): self
        {
            return new self($cents / 100.0, $currency);
        }
        example;
    }
}
