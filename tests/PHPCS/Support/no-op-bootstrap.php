<?php

declare(strict_types=1);

use Eightfold\PHPCodeSniffer\Sniffs\Classes\ThisMemberAccessSniff;
use Eightfold\Tests\PHPCS\Support\Sniffs\Classes\ThisMemberAccessSniff as NoOpThisMemberAccessSniff;

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';
require_once dirname(__DIR__, 3) . '/vendor/squizlabs/php_codesniffer/autoload.php';

if (class_exists(ThisMemberAccessSniff::class)) {
    throw new RuntimeException('The real sniff exists; do not use this bootstrap.');
}

if (!class_alias(NoOpThisMemberAccessSniff::class, ThisMemberAccessSniff::class)) {
    throw new RuntimeException('Unable to register the no-op sniff.');
}
