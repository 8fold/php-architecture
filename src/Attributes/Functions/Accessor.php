<?php

declare(strict_types=1);

namespace Eightfold\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class Accessor
{
    public static function behavior(): string
    {
        return <<<behavior
        Inspects and returns current state or calculated results without mutating state.
        behavior;
    }
    
    public static function definingInvariants(): string
    {
        return <<<qualities
        1. Read-Only / Side-Effect Free: Mutates zero instance variables or external resources; may set initial state (caching).
        2. Typed Value Return: Always produces a typed return value representing the requested information.
        3. Idempotent: Calling the method N times sequentially against the same state yields identical results.
        qualities>>>;
    }
    
    public static function recommendedAlternatives(): string
    {
        return <<<alternatives
        1. Inside a `class`, use a property hook; or, a property hook with a backing private / protected method, if necessary.
        2. Outside a `class` (procedural function), use the properties and objects directly.
        alternatives;
    }
    
    public static function example(): string
    {
        return <<<example
        public function getBalance(): int
        {
            return $this->balance;
        }
        
        public function getBalance(): int
        {
            if (isset($this->balance) === false) {
                $this->balance = 0;
            }
            return $this->balance;
        }
        
        public private(set) int $balance {
            // Invalid target for this attribute
        }
        example;
    }
}
