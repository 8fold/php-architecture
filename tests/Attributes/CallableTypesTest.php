<?php

declare(strict_types=1);

namespace Eightfold\Tests\Attributes;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use Eightfold\Attributes\CallableBaseType;
use Eightfold\Attributes\CallableSubtype;

use Eightfold\Attributes\Callables\Predicate;

final class CallableTypesTest extends TestCase
{
    #[Test]
    #[Group('attributes')]
    #[Group('isolate')]
    public function can_check_validity(): void
    {
        $result = CallableSubtype::isValidAttribute('predicate');

        parent::assertTrue($result);

        $result = CallableSubtype::isValidAttribute(Predicate::class);

        parent::assertTrue($result);

        $result = CallableSubtype::isValidAttribute('Predicate');

        parent::assertTrue($result);

        $result = CallableSubtype::isValidAttribute('DoesNotExist');

        parent::assertFalse($result);
    }

    #[Test]
    #[Group('attributes')]
    #[Group('isolate')]
    public function can_match_subtype(): void
    {
        $sut = CallableBaseType::AbstractMethod;

        $result = $sut->hasSubtype(CallableSubtype::ContractDefinition);

        parent::assertTrue($result);
    }
}
