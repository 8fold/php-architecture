<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPStan\Fixtures\ImportIndex;

use PhpParser\ParserFactory;
use DateTimeImmutable;

/*
 * No class declaration: import grouping is a file-level convention.
 * Expected: one eightfold.classImportsShouldBeGrouped diagnostic at the
 * ParserFactory import; the PSL class DateTimeImmutable belongs first.
 * There are no extends or implements blocks; empty blocks are omitted.
 */
$parser = (new ParserFactory())->createForNewestSupportedVersion();
$date = new DateTimeImmutable('2026-01-01');

echo count($parser->parse('<?php echo 1;') ?? []) . $date->format('c');
