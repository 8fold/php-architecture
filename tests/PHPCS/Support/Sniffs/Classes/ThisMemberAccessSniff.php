<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPCS\Support\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/** Temporary no-op double for reviewing the tests before implementation. */
final class ThisMemberAccessSniff implements Sniff
{
    public function register(): array
    {
        return [T_OBJECT_OPERATOR, T_NULLSAFE_OBJECT_OPERATOR];
    }

    public function process(File $file, int $stackPtr): void
    {
    }
}
