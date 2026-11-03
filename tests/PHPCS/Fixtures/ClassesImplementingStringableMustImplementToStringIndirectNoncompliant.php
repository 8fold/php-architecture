<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPCS\Fixtures\ClassesImplementingStringableMustImplementToString\Indirect;

use Eightfold\Attributes\Classes\Subclass;

require_once __DIR__ . '/ClassesImplementingStringableMustImplementToStringIndirectParentNoncompliant.php';

/*
 * PHPStan should report each of the six named classes and the anonymous class
 * once. PHPCS must not guess whether a parent or another interface supplies
 * ToString; cases it cannot resolve remain PHPStan's responsibility.
 */

#[Subclass(rationale: 'Exercises inherited native stringability.')]
final class InheritsNativeStringability extends NativeStringableParent
{
}

#[Subclass(rationale: 'Exercises a direct override with an indirect parent.')]
final class OverridesNativeStringification extends NativeStringableParent
{
    public function __toString(): string
    {
        return 'override';
    }
}

abstract class ImplementsDerivedNativeContract implements DerivedNativeContract
{
}

final class ImplementsNativeContractWithTrait implements DerivedNativeContract
{
    use StringableTrait;
}

final class UsesTraitWithoutSuiteContract
{
    use StringableTrait;
}

final class DeclaresMagicWithUnrelatedContract implements UnrelatedContract
{
    public function value(): string
    {
        return 'value';
    }

    public function __toString(): string
    {
        return 'value';
    }
}

function noncompliantIndirectAnonymous(): \Stringable
{
    return new #[Subclass(rationale: 'Exercises inherited anonymous-class stringability.')]
    class extends NativeStringableParent {
    };
}
