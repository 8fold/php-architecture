<?php

declare(strict_types=1);

namespace Eightfold\Analysis\PHPStan\Fixtures;

use Eightfold\Attributes\RecordObject;

/**
 * Deliberately impure: exercises every check in RecordPurityRule.
 */
#[RecordObject]
final class ImpureRecordObject
{
    public string $mutable;

    public static string $shared;

    public string $id {
        get => strtolower($this->token);
        set => $this->token = $value;
    }

    public function __construct(
        public string $token
    ) {
    }

    public static function init(string $token): self
    {
        return new self($token);
    }

    public function rename(string $token): void
    {
        $this->mutable = $token;
    }
}
