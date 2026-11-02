<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPStan\Fixtures;

final class BooleanNegationMustBeExplicitNoncompliant
{
    public static function variable(bool $flag): bool
    {
        return !$flag;
    }

    public static function condition(bool $flag): bool
    {
        if (!$flag) {
            return true;
        }
        return false;
    }

    public static function predicate(bool $flag): bool
    {
        return !self::variable($flag);
    }

    /** @param array<string, mixed> $values */
    public static function nonempty(array $values): bool
    {
        return !empty($values);
    }

    /** @param array<string, mixed> $values */
    public static function unsetValue(array $values): bool
    {
        return !isset($values['value']);
    }

    /** @param list<string> $values */
    public static function membership(string $needle, array $values): bool
    {
        return !in_array($needle, $values, true);
    }

    public static function notInstance(object $value): bool
    {
        return !($value instanceof \stdClass);
    }

    public static function compound(bool $left, bool $right): bool
    {
        return !$left && $right;
    }

    public static function normalized(bool $flag): bool
    {
        return !!$flag;
    }
}
