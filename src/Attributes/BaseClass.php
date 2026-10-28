<?php

namespace Eightfold\Attributes;

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
