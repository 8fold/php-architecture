<?php

declare(strict_types=1);

namespace Eightfold\Efqs\Sniffs\Classes;

use Eightfold\Analysis\ClassesImplementingStringableMustImplementToString\PHPCSSniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

final class ClassesImplementingStringableMustImplementToStringSniff implements Sniff
{
    private readonly PHPCSSniff $rule;

    public function __construct()
    {
        $this->rule = new PHPCSSniff();
    }

    /** @return list<int|string> */
    public function register(): array
    {
        return $this->rule->register();
    }

    public function process(File $file, int $stackPtr): void
    {
        $this->rule->process($file, $stackPtr);
    }
}
