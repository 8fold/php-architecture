<?php

declare(strict_types=1);

/*
 * No namespace or class declaration: file-level analysis must still run.
 * Expected: two FullyQualified errors, at \DateTimeImmutable and \DateTime.
 * The global constant \PHP_EOL must not be reported.
 */
$date = new \DateTimeImmutable('2026-01-01');

echo $date->format(\DateTime::ATOM) . \PHP_EOL;
