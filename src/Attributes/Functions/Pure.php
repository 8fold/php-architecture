<?php

declare(strict_types=1);

namespace Eightfold\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class Pure
{
    public static function behavior(): string
    {
        return <<<behavior
        Performs a deterministic transformation dependent solely on its input parameters.
        behavior;
    }
    
    public static function definingInvariants(): string
    {
        return <<<qualities
        1. Determinism: The same input arguments ALWAYS produce the exact same output value.
        2. Zero Side Effects: Reads no global state, performs no I/O, and mutates no external variables or arguments.
        3. Referential Transparency: The function call can be safely replaced by its evaluation result.
        4. Functional Composability: Directly compatible with PHP 8.5's Pipe Operator (|>) and callables.
        qualities>>>;
    }
    
    public static function recommendedAlternatives(): string
    {
        return '';
    }
    
    public static function example(): string
    {
        return <<<example
        function sanitizeCurrencyCode(string $rawCode): string
        {
            return strtoupper(trim($rawCode));
        }

        function formatAmount(float $amount): string
        {
            return sprintf('$%.2f USD', $amount);
        }
        example;
    }
}
