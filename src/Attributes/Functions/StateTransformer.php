<?php

declare(strict_types=1);

namespace Eightfold\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class StateTransformer
{
    public static function behavior(): string
    {
        return <<<behavior
        Creates and returns a modified copy or cloned instance (`self`/`static`) with updated state while leaving the original `$this` instance completely untouched (immutable copy-on-write transformation). (See also StateTransformerThis)
        behavior;
    }
    
    public static function definingInvariants(): string
    {
        return <<<qualities
        1. Immutability Preservation ($this Isolation): Never mutates the state of the caller object instance ($this).
        2. Fresh Instance Yield (self/static Return): Always produces and returns a new instance (`self`/`static`) containing the updated state ($result !== $this).
        3. Native Copy-on-Write Execution: Leverages PHP 8.5's `clone($this, ['prop' => $value])` syntax to construct fresh state representations.
        qualities>>>;
    }
    
    public static function recommendedAlternatives(): string
    {
        return '';
    }
    
    public static function example(): string
    {
        return <<<example
        #[StateTransformer]
        public function withAmount(float $newAmount): self
        {
            // PHP 8.5 native clone with inline property modification
            return clone($this, [
                'amount' => $newAmount,
            ]);
        }
        
        #[StateTransformer]
        public function withCurrency(string $currency): self
        {
            return new self($this->amount, $currency);
        }
        example;
    }
}
