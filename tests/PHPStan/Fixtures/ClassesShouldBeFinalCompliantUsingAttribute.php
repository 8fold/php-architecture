<?php

namespace Eightfold\Tests\PHPStan\Fixtures;

use Eightfold\Attributes\Classes\BaseClass;

#[BaseClass('This fixture permits extension.')]
class ClassesShouldBeFinalCompliantUsingAttribute extends \DateTime
{
}
