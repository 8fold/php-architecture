<?php

declare(strict_types=1);

namespace Eightfold\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class Predicate
{
    public static function behavior(): string
    {
        return <<<behavior
        Evaluates a domain condition against the subject and returns a boolean decision.
        behavior;
    }
    
    public static function definingInvariants(): string
    {
        return <<<qualities
        1. Non-Mutating Decision Boundary: Inspects state without altering it; ideal for conditional branching.
        2. Strict Boolean Return: Guarantees a deterministic true or false evaluation.
        qualities>>>;
    }
    
    public static function recommendedAlternatives(): string
    {
        return <<<alternatives
        1. Inside a `class`, use a property hook; or, a property hook with a backing private / protected method, if necessary.
        2. Outside a `class` (procedural function), use the property or object directly.
        alternatives;
    }
    
    public static function example(): string
    {
        return <<<example
        public function hasSufficientBalance(float $requiredAmount): bool
        {
            return $this->balance >= $requiredAmount;
        }
        example;
    }
}
