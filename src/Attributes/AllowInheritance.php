<?php

declare(strict_types=1);

namespace EightFold\Attributes;

use Attribute;

/**
 * Opts a class out of the strict finality requirement enforced by ClassMustBeFinalRule.
 *
 * Use sparingly and only when inheritance is required by framework design
 * (e.g., Doctrine proxies, base classes intended for extension).
 *
 * This marker turns non-finality into an explicit, auditable decision in the AST.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class AllowInheritance
{
    public function __construct(
        public readonly string $reason = ''
    ) {
    }
}
