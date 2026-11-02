<?php

declare(strict_types=1);

namespace Eightfold\Efqs\Sniffs\Callables;

use Eightfold\Analysis\InstanceMethodsMustNotBeAccessedViaThis\PHPCSSniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * PHPCS registration shell; the rule body is the convention's PHPCS sniff.
 */
final class InstanceMethodsMustNotBeAccessedViaThisSniff implements Sniff
{
    private readonly PHPCSSniff $rule;

    public function __construct()
    {
        $this->rule = new PHPCSSniff();
    }

    public function register(): array
    {
        return $this->rule->register();
    }

    public function process(File $file, int $stackPtr): void
    {
        $this->rule->process($file, $stackPtr);
    }
}
