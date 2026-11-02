<?php

namespace Eightfold\Tests\PHPStan\Fixtures\ClassesShouldBeFinal;

use Eightfold\Attributes\Classes\BaseClass;

#[BaseClass('This fixture permits extension.')]
class Compliant extends \DateTime
{
}
