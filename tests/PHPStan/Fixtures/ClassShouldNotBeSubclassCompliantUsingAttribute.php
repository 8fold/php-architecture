<?php

namespace Eightfold\Tests\PHPStan\Fixtures;

use Eightfold\Attributes\Subclass;

#[Subclass('Required by PHPUnit\Framework\TestCase contract')]
class ClassShouldNotBeSubclassCompliantUsingAttribute
{
}
