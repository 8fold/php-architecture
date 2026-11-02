<?php

namespace Eightfold\Tests\PHPStan\Fixtures;

use Eightfold\Attributes\Classes\Subclass;

#[Subclass('Required by PHPUnit\Framework\TestCase contract')]
class ClassShouldNotBeSubclassCompliantUsingAttribute
{
}
