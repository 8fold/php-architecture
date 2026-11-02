<?php

namespace Eightfold\Tests\PHPStan\Fixtures;

use Eightfold\Attributes\Classes\BaseClass;

#[BaseClass('All subclasses must call')]
class ClassShouldBeFinalCompliantUsingAttribute
{
}
