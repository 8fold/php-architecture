<?php

namespace Eightfold\Attributes\Classes;

use Attribute;

use InvalidArgumentException;

/**
 * Indicates a class is designed to be extended.
 *
 * By default, 8fold PHP projects avoid designing classes to be extended.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class BaseClass
{
    public static function definingInvarients(): string
    {
        return <<<qualities
        Marked as `class` or `abstract class`, MUST allow extension.
        qualities;
    }

    public function __construct(public string $rationale)
    {
        if (trim($rationale) === '') {
            throw new InvalidArgumentException(
                "Rationale required for #[BaseClass('rationale')]."
            );
        }

        $this->rationale = $rationale;
    }
}
