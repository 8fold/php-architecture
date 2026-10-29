<?php

declare(strict_types=1);

namespace Eightfold\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class CommandWithStatus
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
        2. Non-Value Returning: Returns status flag/code (issually `bool`, `int`, or `array`), signaling side-effect execution rather than data evaluation.
        3. Non-Idempotent: Executing the method multiple times alters state sequentially (e.g., repeatedly deducting funds).
        qualities>>>;
    }
}
