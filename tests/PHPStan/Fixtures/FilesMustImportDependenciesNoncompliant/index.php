<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPStan\Fixtures\FilesMustImportDependenciesIndex;

use PhpParser\ParserFactory;
use DateTimeImmutable;

/*
 * No class declaration: the rule analyzes PHPStan\Node\FileNode.
 * Expected: two eightfold.filesMustImportDependencies errors:
 * - line 7, ParserFactory: the PSL import DateTimeImmutable belongs first;
 * - line 21, \DateTime: fully qualified instead of imported.
 * Global functions (count) and constants (\PHP_EOL) are not reported.
 * Empty extends and implements blocks are omitted.
 */
$parser = (new ParserFactory())->createForNewestSupportedVersion();
$date = new DateTimeImmutable('2026-01-01');

echo $date->format(\DateTime::ATOM)
    . count($parser->parse('<?php echo 1;') ?? [])
    . \PHP_EOL;
