<?php

declare(strict_types=1);

namespace Eightfold\Attributes\Callables;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class StateTransformerThis
{
    public static function definingInvariants(): string
    {
        return 'returns `\$this` (not `clone`), mutates internal state only, is instance method (not static)';
    }









    public static function behavior(): string
    {
        // phpcs:disable
        return <<<behavior
        Creates and returns a modified copy or cloned instance (`self`/`static`) with updated state while leaving the original `\$this` instance completely untouched (immutable copy-on-write transformation). (See also StateTransformerThis)
        behavior;
        // phpcs:enable
    }

    public static function definingInvariants(): string
    {
        // phpcs:disable
        return <<<qualities
        1. Immutability Preservation (\$this Isolation): Never mutates the state of the caller object instance (\$this).
        2. Fresh Instance Yield (self/static Return): Always produces and returns a new instance (`self`/`static`) containing the updated state (\$result !== \$this).
        3. Native Copy-on-Write Execution: Leverages PHP 8.5's `clone(\$this, ['prop' => \$value])` syntax to construct fresh state representations.
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
        return <<<example
        #[StateTransformer]
        public function withAmount(float \$newAmount): self
        {
        PHP 8.5 native clone with inline property modification
            return clone(\$this, [
                'amount' => \$newAmount,
            ]);
        }
        
        #[StateTransformer]
        public function withCurrency(string \$currency): self
        {
            return new self(\$this->amount, \$currency);
        }
        example;
        // phpcs:enable
    }
}
