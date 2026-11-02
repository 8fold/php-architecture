<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPStan\Fixtures\ImportCli;

use Eightfold\Attributes\CallableSubtype;
use DateTimeImmutable;

/*
 * No class declaration: imports used by standalone functions still count.
 * Expected: one eightfold.classImportsShouldBeGrouped diagnostic at the
 * CallableSubtype import; the PSL class DateTimeImmutable belongs first.
 */
function describe(DateTimeImmutable $date, CallableSubtype $subtype): string
{
    return $date->format('c') . $subtype->value;
}
