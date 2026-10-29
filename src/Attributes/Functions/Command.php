<?php

declare(strict_types=1);

namespace Eightfold\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class Command
{
    public static function behavior(): string
    {
        return <<<behavior
        Executes an action, modifies object state or database state, or triggers side effects.
        behavior;
    }
    
    public static function definingInvariants(): string
    {
        return <<<qualities
        1. Impure & State-Altering: Causes a state transition in the object, database, or external system.
        2. Non-Value Returning: Returns `void`, signaling side-effect execution rather than data evaluation; throws error on failure.
        3. Non-Idempotent: Executing the method multiple times alters state sequentially (e.g., repeatedly deducting funds).
        qualities>>>;
    }
    
    public static function recommendedAlternatives(): string
    {
        return '';
    } 
    
    public static function example(): string
    {
        return <<<example
        public/private/protected delete(): void
        {
            // remove instance from database
            // throw on failure
        }
        
        delete(Record $record): void
        {
            // remove instance from database
            // throw on failure
        }
        example>>>;
    }
}
