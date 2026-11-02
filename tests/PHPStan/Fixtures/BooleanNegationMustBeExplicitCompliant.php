<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPStan\Fixtures;

final class BooleanNegationMustBeExplicitCompliant
{
    public static function variable(bool $flag): bool
    {
        return $flag === false;
    }

    public static function positive(bool $flag): bool
    {
        if ($flag) {
            return true;
        }
        return false;
    }

    public static function predicate(bool $flag): bool
    {
        return self::variable($flag) === false;
    }

    /** @param array<string, mixed> $values */
    public static function nonempty(array $values): bool
    {
        return empty($values) === false;
    }

    /** @param array<string, mixed> $values */
    public static function unsetValue(array $values): bool
    {
        return isset($values['value']) === false;
    }

    /** @param list<string> $values */
    public static function membership(string $needle, array $values): bool
    {
        return in_array($needle, $values, true) === false;
    }

    public static function notInstance(object $value): bool
    {
        return ($value instanceof \stdClass) === false;
    }

    public static function compound(bool $left, bool $right): bool
    {
        return $left === false && $right;
    }

    public static function comparison(int $value): bool
    {
        return $value !== 0;
    }

    public static function normalized(bool $flag): bool
    {
        return $flag === true;
    }
}
