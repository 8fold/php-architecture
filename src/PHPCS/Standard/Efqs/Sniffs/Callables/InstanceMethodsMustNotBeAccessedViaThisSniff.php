<?php

declare(strict_types=1);

namespace Eightfold\Efqs\Sniffs\Callables;

use Eightfold\PHPCS\Sniffs\Callables\InstanceMethodsMustNotBeAccessedViaThis;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * PHPCS registration shell; the rule body is InstanceMethodsMustNotBeAccessedViaThis.
 */
final class InstanceMethodsMustNotBeAccessedViaThisSniff implements Sniff
{
    private readonly InstanceMethodsMustNotBeAccessedViaThis $rule;

    public function __construct()
    {
        $this->rule = new InstanceMethodsMustNotBeAccessedViaThis();
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
