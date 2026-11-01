<?php

declare(strict_types=1);

namespace Eightfold\Attributes;

enum Returns: string
{
    case NeverType   = 'never';
    case VoidType    = 'void';
    case BooleanType = 'bool';
    case SelfType    = 'self'; // self or static
    case Other       = 'other'; // Non-void value, object, or scalar
}
