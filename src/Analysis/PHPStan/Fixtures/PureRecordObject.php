<?php

declare(strict_types=1);

namespace Eightfold\Analysis\PHPStan\Fixtures;

use Eightfold\Attributes\RecordObject;

/**
 * The sanctioned record shape: promoted readonly properties (required
 * first, nullable last) and a computed get-only hook.
 */
#[RecordObject]
final class PureRecordObject
{
    public ?string $token {
        get => $this->session?->id;
    }

    public function __construct(
        public readonly string $id,
        public readonly ?PureRecordObject $session = null,
    ) {
    }
}
