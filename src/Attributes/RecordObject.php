<?php

declare(strict_types=1);

namespace Eightfold\Attributes;

use Attribute;

/**
 * Canonical description of RecordObject
 *
 * Marks a record object representing a database row.
 *
 * Strict purity rules apply:
 * - Promoted properties and property hooks only.
 * - No public static initializers (call the public constructor directly).
 * - Constructor mirrors table constraints (NOT NULL required first, nullable last).
 * - Must be a final class.
 *
 * Do not repeat #[DataObject] on a class carrying #[RecordObject].
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class RecordObject
{
}
