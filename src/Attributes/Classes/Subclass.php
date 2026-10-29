<?php

namespace Eightfold\Attributes\Classes;

use Attribute;

use InvalidArgumentException;

/**
 * Indicates class extends another class.
 *
 * By default, 8fold PHP projects avoid extending classes.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class Subclass
{
    public static function definingInvarients(): string
    {
        return <<<qualities
        Marked as `class` or `abstract class`, MUST use `extends`.
        qualities;
    }

    public function __construct(public string $rationale)
    {
        if (trim($rationale) === '') {
            throw new InvalidArgumentException(
                "Rationale required for #[Subclass('rationale')]."
            );
        }

        $this->rationale = $rationale;
    }
}
