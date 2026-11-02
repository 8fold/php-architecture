<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPCS\Fixtures\ImportCli;

/*
 * No class declaration: names inside standalone functions must be checked.
 * Expected: three FullyQualified errors, at the parameter type, return type,
 * and \DateTimeImmutable::createFromMutable call.
 */
function immutable(\DateTime $date): \DateTimeImmutable
{
    return \DateTimeImmutable::createFromMutable($date);
}
