<?php

namespace Eightfold\Tests\PHPStan\Fixtures;

use Eightfold\Attributes\BaseClass;

#[BaseClass('All subclasses must call')]
class ClassShouldBeFinalCompliantUsingAttribute
{
}
