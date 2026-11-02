<?php

namespace Eightfold\Tests\PHPStan\Fixtures;

use Eightfold\Attributes\Classes\BaseClass;

#[BaseClass('This fixture permits extension.')]
class ClassShouldBeFinalCompliantUsingAttribute extends \DateTime
{
}
