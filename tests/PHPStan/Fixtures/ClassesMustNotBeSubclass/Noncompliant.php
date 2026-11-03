<?php

namespace Eightfold\Tests\PHPStan\Fixtures\ClassesMustNotBeSubclass;

use DateTime;

class Noncompliant extends DateTime
{
}
