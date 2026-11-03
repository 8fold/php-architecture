<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPCS\Fixtures\FilesMustImportClassNames;

use ArrayObject;
use Countable;
use DateTime;
use DateTimeImmutable;
use Exception;
use Override;
use RuntimeException;
use SplObjectStorage;
use Stringable;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_ as ClassNode;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;

use Eightfold\Attributes\CallableSubtype;
use Eightfold\Attributes\Classes\BaseClass;

trait CompliantTrait
{
}

/**
 * Draft fixture for issue #46; must produce no diagnostics.
 *
 * Every class-like name in code is imported directly, including built-in PHP
 * classes. Aliased imports (ClassNode) and unqualified names from the current
 * namespace (CompliantTrait) are compliant. Global
 * functions (\strlen, \count), constants (\PHP_EOL), and names in strings,
 * comments, and docblocks such as \PhpParser\Node are outside this
 * convention. PHPCS cannot classify symbols, so the PHPStan fixtures verify
 * import grouping; the grouping here matches them.
 *
 * @implements Rule<ClassNode>
 */
#[BaseClass('Fixture for issue #46.')]
abstract class Compliant extends DateTime implements
    Stringable,
    Rule
{
    use CompliantTrait;

    /** @param ArrayObject<int, mixed>|SplObjectStorage<object, mixed> $items */
    public function __construct(
        private CallableSubtype $subtype,
        private ArrayObject|SplObjectStorage $items,
        private ?Countable $counter = null,
    ) {
        parent::__construct();
    }

    public function getNodeType(): string
    {
        return ClassNode::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        try {
            $created = new DateTimeImmutable($node->getType());
        } catch (Exception $exception) {
            throw new RuntimeException('\PhpParser\Node', 0, $exception);
        }

        return $this->items instanceof ArrayObject
            && DateTimeImmutable::createFromMutable($this) > $created
            ? []
            : [];
    }

    #[Override]
    public function __toString(): string
    {
        return $this->subtype->value
            . \strlen(\PHP_EOL)
            . \count($this->items)
            . ($this->counter?->count() ?? 0);
    }
}
