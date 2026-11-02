<?php

declare(strict_types=1);

namespace Eightfold\Interfaces;

/**
 * Fixture-only model of the proposed contract, not Composer-autoloaded.
 * Remove this model when the production interface is introduced.
 */
interface ToString extends \Stringable
{
    public function toString(): string;
}
