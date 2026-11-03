<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPCS\Fixtures\ClassesImplementingStringableMustImplementToString\Indirect;

require_once __DIR__ . '/ClassesImplementingStringableMustImplementToStringIndirectCompliant.php';

/*
 * PHPStan should report this class once: its trait makes it stringable, but
 * it does not implement the suite contract. The trait itself is not a target.
 */

abstract class NativeStringableParent
{
    use StringableTrait;
}
