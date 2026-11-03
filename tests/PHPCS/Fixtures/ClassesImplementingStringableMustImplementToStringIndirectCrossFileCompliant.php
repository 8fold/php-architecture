<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPCS\Fixtures\ClassesImplementingStringableMustImplementToString\Indirect;

use Eightfold\Attributes\Classes\Subclass;

require_once __DIR__ . '/ClassesImplementingStringableMustImplementToStringIndirectCompliant.php';

/*
 * No convention diagnostics are expected. Both the parent and the interface
 * supplying the suite contract are declared in another file.
 */

#[Subclass(rationale: 'Exercises a suite contract inherited across files.')]
final class CrossFileSuiteParentOverride extends SuiteStringableParent
{
    public function __toString(): string
    {
        return 'override';
    }
}

final class CrossFileSuiteContract implements DerivedSuiteContract
{
    public function __toString(): string
    {
        return 'derived';
    }

    public function toString(): string
    {
        return 'derived';
    }
}
