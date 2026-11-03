<?php

namespace Eightfold\Tests\PHPStan\Fixtures\ClassesShouldNotBeSubclass;

use Eightfold\Attributes\Classes\Subclass;

#[Subclass('This fixture requires a subclass.')]
class Compliant extends \DateTime
{
}
