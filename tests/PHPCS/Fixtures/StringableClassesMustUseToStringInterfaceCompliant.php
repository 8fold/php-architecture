<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPCS\Fixtures\StringableClassesMustUseToStringInterface;

use Eightfold\Interfaces\ToString;
use Eightfold\Interfaces\ToString as SuiteToString;
use Stringable;

/*
 * Shared PHPCS/PHPStan draft: no convention diagnostics are expected.
 * Traits, interfaces and non-stringable enums are provisional non-targets.
 */

final class ImportedSuiteStringable implements ToString
{
    use TraitStringabilityDeclaration;

    public function __toString(): string
    {
        return 'value';
    }
}

final class AliasedSuiteStringable implements SuiteToString
{
    public function __toString(): string
    {
        return 'value';
    }
}

final class FullyQualifiedSuiteStringable implements \Eightfold\Interfaces\ToString
{
    public function __toString(): string
    {
        return 'value';
    }
}

final class NativeThenSuiteStringable implements Stringable, ToString
{
    public function __toString(): string
    {
        return 'value';
    }
}

final class SuiteThenNativeStringable implements ToString, Stringable
{
    public function __toString(): string
    {
        return 'value';
    }
}

abstract class AbstractSuiteStringable implements ToString
{
}

final class OrdinaryToStringMethod
{
    public function toString(): string
    {
        return '__toString';
    }
}

final class NonStringableValue
{
}

interface NativeStringabilityDeclaration extends Stringable
{
    public function __toString(): string;
}

trait TraitStringabilityDeclaration
{
    public function __toString(): string
    {
        return 'value';
    }
}

enum NonStringableEnum
{
    case Value;
}

function compliantAnonymousStringable(): Stringable
{
    return new class implements SuiteToString {
        public function __toString(): string
        {
            return 'value';
        }
    };
}
