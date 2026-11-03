<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPStan\Fixtures\ClassesImplementingStringableMustImplementToString\Indirect;

use Eightfold\Attributes\Classes\Subclass;
use Eightfold\Interfaces\ToString;

/*
 * No convention diagnostics are expected. The interfaces and trait are not
 * targets; classes may inherit or indirectly implement the suite contract.
 */

interface DerivedSuiteContract extends ToString
{
}

interface DerivedNativeContract extends \Stringable
{
}

interface UnrelatedContract
{
    public function value(): string;
}

trait StringableTrait
{
    public function __toString(): string
    {
        return 'trait value';
    }
}

abstract class SuiteStringableParent implements ToString
{
    use StringableTrait;

    public function toString(): string
    {
        return 'suite value';
    }
}

abstract class OrdinaryParent
{
}

#[Subclass(rationale: 'Exercises inherited suite-interface compliance.')]
final class InheritsSuiteContract extends SuiteStringableParent
{
}

#[Subclass(rationale: 'Exercises an override of inherited stringability.')]
final class OverridesInheritedStringification extends SuiteStringableParent
{
    public function __toString(): string
    {
        return 'override';
    }
}

final class ImplementsDerivedSuiteContract implements DerivedSuiteContract
{
    use StringableTrait;

    public function toString(): string
    {
        return 'derived';
    }
}

final class DeclaresMagicAndDerivedSuiteContract implements DerivedSuiteContract
{
    public function __toString(): string
    {
        return 'derived';
    }

    public function toString(): string
    {
        return 'derived';
    }
}

final class NativeAndDerivedSuiteContract implements \Stringable, DerivedSuiteContract
{
    use StringableTrait;

    public function toString(): string
    {
        return 'derived';
    }
}

#[Subclass(rationale: 'Exercises a non-stringable parent.')]
final class OrdinaryChild extends OrdinaryParent
{
}

function compliantIndirectAnonymous(): ToString
{
    return new #[Subclass(rationale: 'Exercises inherited anonymous-class compliance.')]
    class extends SuiteStringableParent {
        public function __toString(): string
        {
            return 'anonymous';
        }
    };
}
