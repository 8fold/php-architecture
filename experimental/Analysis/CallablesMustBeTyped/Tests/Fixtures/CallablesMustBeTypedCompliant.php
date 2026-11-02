<?php

namespace Eightfold\Experimental\Analysis\CallablesMustBeTyped\Tests\Fixtures;

use Stringable;
use NoDiscard;

use Eightfold\Attributes\NamedConstructor;

class CallablesMustBeTypedCompliant implements Stringable
{
    // Exempted constructor
    public function __construct(
        private readonly string $name
    ) {
    }

    //#[Command]
    public function command(): void // L19
    {
        $this->name = '8fold';
        return;
    }

    //#[CommandWithStatus]
    //#[NoDiscard]
    public function commandWithStatus(): bool // L27
    {
        $this->name = '8fold';
        return true;
    }

    //#[Query]
    public function getName(): string // L34
    {
        return $this->name;
    }

    //#[Query]
    public function setName(string $something = 'Eightfold'): string // L40
    {
        $this->name = $something;
        return $this->name;
    }

    //#[StateTransformer]
    public function setNameStateTransformer( // L47
        string $something = 'Eightfold'
    ): self {
        // property overrides
        return clone($this, ['name' => $something]);
    }

    //#[StateTransformer]
    public function setNameStateTransformer( // L55
        string $something = 'Eightfold'
    ): self {
        // < PHP 8.5
        return new self($something);
    }

    //#[StateTransformerThis]
    public function setNameStateTransformerThis(
        string $something = 'Eightfold'
    ): self {
        $this->name = $something;
        return $this;
    }

    //#[StateTransformerThis]: Only works if class is not final
    public function setNameStateTransformerThisAlt(
        string $something = 'Eightfold'
    ): static {
        $this->name = $something;
        return $this;
    }

    //#[NamedConstructor]
    private static function init(): self // L10
    {
        return new CallablesMustBeTypedCompliant();
    }

    //#[Factory]: Because returns a `new` instance, otherwise #[Query]?
    private static function init(): self // L10
    {
        return new CallablesMustBeTypedCompliant();
    }

    //#[Handler]??

    //#[Predicate]: Compares using $this
    public function isSomething(): bool
    {
        return $this->name === 'Something';
    }

    //#[Check]
    //#[NoDiscard]
    public function hasAccess(User $user): bool
    {
        if ($user->role === 'Admin') {
            return true;
        }
        self::
    }

    //#[Assertion]
    public function failedAccess(): never
    {
        throw new Exception("Error Processing Request", 1);

    }

    //#[Pure]
    public static function pureStatic(int $multiplier): int
    {
        return 13 * $multiplier;
    }

    //#[Pure]: Also, probably better as static or standalone
    public function getDob(): string // L31
    {
        return "YYYY-MM-DD";
    }

    //#[Middlewar]?

    //#[Voter]?

    //#[ContractDefinition]
    abstract public function getId(): int; // L45

    //#[MagicMethod]
    public function __toString(): string // L48
    {
        return '';
    }
}

//#[StandaloneFunction]
function standaloneFunction(): bool // L55
{
    // Converted to a standard anonymous closure
    $triple = function (int $n) use ($factor): int { // L58
        return $n * $factor;
    };
    return $triple(4) > 10;
}
