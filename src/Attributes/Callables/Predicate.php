<?php

declare(strict_types=1);

namespace Eightfold\Attributes\Callables;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class Predicate
{
    public static function behavior(): string
    {
        // phpcs:disable
        return <<<behavior
        Evaluates a domain condition against the subject and returns a boolean decision.
        behavior;
        // phpcs:enable
    }

    public static function definingInvariants(): string
    {
        // phpcs:disable
        return <<<qualities
        1. Non-Mutating Decision Boundary: Inspects state without altering it or causing side effects.
        2. Strict Boolean Return: Guarantees a deterministic true or false evaluation.
        qualities;
        // phpcs:enable
    }

    public static function recommendedAlternatives(): string
    {
        // phpcs:disable
        return <<<alternatives
        1. Inside a `class`, use a property hook; or, a property hook with a backing private / protected method, if necessary.
        2. Outside a `class` (procedural function), use the property or object directly.
        alternatives;
        // phpcs:enable
    }

    public static function example(): string
    {
        // phpcs:disable
        return <<<example
        public function hasSufficientBalance(float \$requiredAmount): bool
        {
            return \$this->balance >= \$requiredAmount;
        }
        example;
        // phpcs:enable
    }
}
