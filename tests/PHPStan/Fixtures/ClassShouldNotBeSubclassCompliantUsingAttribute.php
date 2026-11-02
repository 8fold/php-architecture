<?php

namespace Eightfold\Tests\PHPStan\Fixtures;

use Eightfold\Attributes\Classes\Subclass;

#[Subclass('This fixture requires a subclass.')]
class ClassShouldNotBeSubclassCompliantUsingAttribute extends \DateTime
{
}
