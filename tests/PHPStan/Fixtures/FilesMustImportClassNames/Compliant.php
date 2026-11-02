<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPStan\Fixtures\FilesMustImportClassNames;

use DateTime;

use Stringable;
use PHPStan\Rules\Rule;

use InvalidArgumentException;

use Countable;

use Override;

use PhpParser\Node\Stmt\Class_;

use PhpParser\Node;

use PHPStan\Analyser\Scope;

use Eightfold\Attributes\CallableSubtype;

use Eightfold\Attributes\Functions\Pure;

/**
 * Draft fixture for issue #46; must produce no diagnostics.
 *
 * The rule analyzes PHPStan's file-level node, PHPStan\Node\FileNode, whose
 * getNodes() returns every top-level statement. It therefore covers every
 * import and name in the file, with or without a class. Every class-like name
 * is imported, and the imports are grouped.
 *
 * Provisional block order, one blank line between blocks:
 *
 * 1. Extended parent class.
 * 2. Implemented interfaces, in declaration order.
 * 3. PHP standard library (PSL): classes, interfaces, traits, attributes.
 * 4. Third-party, one group per Composer package, packages sorted
 *    alphabetically (nikic/php-parser, phpstan/phpstan); each package
 *    split into classes, interfaces, traits, attributes.
 * 5. First-party, grouped like third-party (Eightfold\Attributes).
 *
 * Names appear once, in the earliest applicable block; Rule is third-party
 * but belongs to the implements block. Names within a block other than
 * implements are sorted alphabetically. Empty blocks are omitted. Enums
 * (CallableSubtype) are provisionally grouped with classes.
 *
 * @implements Rule<Class_>
 */
final class Compliant extends DateTime implements
    Stringable,
    Rule
{
    public function getNodeType(): string
    {
        return Class_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->name === null) {
            throw new InvalidArgumentException('Anonymous classes are ignored.');
        }

        return [];
    }

    #[Override]
    public function __toString(): string
    {
        return parent::format('c');
    }

    #[Pure]
    public function total(Countable $items, CallableSubtype $subtype): int
    {
        return count($items);
    }
}
