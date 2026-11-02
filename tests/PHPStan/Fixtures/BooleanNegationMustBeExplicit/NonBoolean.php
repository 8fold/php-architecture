<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPStan\Fixtures\BooleanNegationMustBeExplicit;

// These truthiness checks need an intent review, not a boolean-only diagnostic.
final class NonBoolean
{
    public static function nullable(?bool $value): bool
    {
        return !$value;
    }

    public static function unknown(mixed $value): bool
    {
        return !$value;
    }

    public static function text(string $value): bool
    {
        return !$value;
    }

    public static function number(int $value): bool
    {
        return !$value;
    }

    /** @param list<mixed> $value */
    public static function values(array $value): bool
    {
        return !$value;
    }
}
