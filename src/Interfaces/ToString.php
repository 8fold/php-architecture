<?php

declare(strict_types=1);

namespace Eightfold\Interfaces;

use Stringable;

interface ToString extends Stringable
{
    public function toString(): string;
}
