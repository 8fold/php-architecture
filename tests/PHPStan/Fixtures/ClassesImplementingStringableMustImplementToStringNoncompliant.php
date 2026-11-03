<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPStan\Fixtures\ClassesImplementingStringableMustImplementToString;

use Stringable;
use Stringable as NativeStringable;

/*
 * One convention diagnostic is expected at each offending class
 * declaration: eight named classes and two anonymous classes.
 * Both triggers must not produce duplicate errors. The interface and the
 * NonStringableContainer are not targets; nested methods belong to their class.
 */

final class ExplicitNativeStringable implements \Stringable
{
    public function __toString(): string
    {
        return 'value';
    }
}

final class ImportedNativeStringable implements Stringable
{
    public function __toString(): string
    {
        return 'value';
    }
}

final class AliasedNativeStringable implements NativeStringable
{
    public function __toString(): string
    {
        return 'value';
    }
}

final class ImplicitStringable
{
    public function __toString(): string
    {
        return 'value';
    }
}

final class CaseInsensitiveMagicStringable
{
    public function __TOSTRING(): string
    {
        return 'value';
    }
}

abstract class AbstractNativeStringable implements \Stringable
{
}

abstract class AbstractMagicStringable
{
    abstract public function __toString(): string;
}

// The same short name is not the suite contract.
interface ToString extends Stringable
{
}

final class UnrelatedToStringStringable implements ToString
{
    public function __toString(): string
    {
        return 'value';
    }
}

final class NonStringableContainer
{
    public static function value(): Stringable
    {
        return new class {
            public function __toString(): string
            {
                return 'value';
            }
        };
    }
}

function noncompliantAnonymousNativeStringable(): Stringable
{
    return new class implements NativeStringable {
        public function __toString(): string
        {
            return 'value';
        }
    };
}
