<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPStan\Fixtures;

use Countable;
use DateTime;
use Eightfold\Attributes\CallableSubtype;
use Eightfold\Attributes\Functions\Pure;
use InvalidArgumentException;
use Override;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Stringable;

/**
 * Draft fixture for issue #46; the expected diagnostic is provisional.
 * The convention applies to file-level imports, even without a class.
 * Sibling directory fixtures cover index.php, cli/, and extensionless bin/ scripts.
 *
 * The same imports as ClassImportsShouldBeGroupedCompliant, in one
 * alphabetical block, as many editors produce.
 *
 * Expected: one eightfold.classImportsShouldBeGrouped tip-level diagnostic
 * at the first import that departs from the expected order (line 7,
 * Countable; DateTime is expected first), naming the expected order. An
 * alternative is one diagnostic per misplaced import.
 *
 * @implements Rule<Class_>
 */
final class ClassImportsShouldBeGroupedNoncompliant extends DateTime implements
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
