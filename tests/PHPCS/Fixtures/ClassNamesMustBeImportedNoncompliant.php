<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPCS\Fixtures;

use PHPStan\Rules;

trait ClassNamesMustBeImportedNoncompliantTrait
{
}

/**
 * Draft fixture for issue #46; expected diagnostics are provisional.
 *
 * Expected: one Efqs.Namespaces.ClassNamesMustBeImported.FullyQualified error
 * for each fully qualified class-like name in code, including built-in PHP
 * classes, at the name's first token: the attribute, extends, implements,
 * trait use, property and parameter types, union and nullable types, `new`,
 * static calls, `::class`, `instanceof`, and `catch`.
 *
 * Partially qualified names that resolve through an imported namespace
 * (Rules\Rule) produce a separate
 * Efqs.Namespaces.ClassNamesMustBeImported.Qualified error; whether that case
 * belongs in this convention is an open question.
 *
 * Not reported: the namespace declaration, `use` import statements, global
 * functions (\strlen, \count) and constants (\PHP_EOL), and names inside
 * strings, comments, and docblocks.
 *
 * @implements Rules\Rule<\PhpParser\Node\Stmt\Class_>
 */
#[\Eightfold\Attributes\Classes\BaseClass('Fixture for issue #46.')]
abstract class ClassNamesMustBeImportedNoncompliant extends \DateTime implements
    \Stringable,
    Rules\Rule
{
    use \Eightfold\Tests\PHPCS\Fixtures\ClassNamesMustBeImportedNoncompliantTrait;

    /** @param \ArrayObject<int, mixed>|\SplObjectStorage<object, mixed> $items */
    public function __construct(
        private \Eightfold\Attributes\CallableSubtype $subtype,
        private \ArrayObject|\SplObjectStorage $items,
        private ?\Countable $counter = null,
    ) {
        parent::__construct();
    }

    public function getNodeType(): string
    {
        return \PhpParser\Node\Stmt\Class_::class;
    }

    public function processNode(\PhpParser\Node $node, \PHPStan\Analyser\Scope $scope): array
    {
        try {
            $created = new \DateTimeImmutable($node->getType());
        } catch (\Exception $exception) {
            throw new \RuntimeException('Not reported: \PhpParser\Node', 0, $exception);
        }

        return $this->items instanceof \ArrayObject
            && \DateTimeImmutable::createFromMutable($this) > $created
            ? []
            : [];
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->subtype->value
            . \strlen(\PHP_EOL)
            . \count($this->items)
            . ($this->counter?->count() ?? 0);
    }
}
