<?php

declare(strict_types=1);

namespace MTM\Analysis\PHPStan\Fixtures;

use MTM\Shared\Attributes\Record;

/**
 * The sanctioned record shape: promoted readonly properties (required
 * first, nullable last) and a computed get-only hook.
 */
#[Record]
final class PureRecord
{
    public ?string $token {
        get => $this->session?->id;
    }

    public function __construct(
        public readonly string $id,
        public readonly ?PureRecord $session = null,
    ) {
    }
}
