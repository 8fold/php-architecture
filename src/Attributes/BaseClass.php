<?php

use Attribute;

use InvalidArgumentException;

/**
 * Indicates a class is designed to be extended.
 *
 * By default, 8fold PHP projects avoid designing classes to be extended.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class BaseClass
{
    public string $rationale;

    public function __construt(string $rationale)
    {
        if (trim($rationale) === '') {
            throw new InvalidArgumentException(
                "Rationale required for #[BaseClass('rationale')]."
            );
        }

        $this->rationale = $rationale;
    }
}
