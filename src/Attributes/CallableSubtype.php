<?php

declare(strict_types=1);

namespace Eightfold\Attributes;

enum CallableSubtype: string
{
    case NotFound = '';

    /** Command-Query Separation */

    /**
     * State-altering action (: void).
     *
     * Mutates $this/self or external state, returns strictly void, and throws on failure.
     */
    case Command = 'command';

    /**
     * State-altering action with outcome telemetry.
     *
     * Mutates state while returning a status flag annotated with #[\NoDiscard].
     */
    case CommandWithStatus = 'command_with_status';

    /**
     * Also known as Accessor: Read-only state inspection.
     *
     * Inspects or calculates derived state from $this without mutating state
     * or causing side effects.
     */
    case Query = 'query';

    /**
     * Copy-on-write transformation.
     *
     * Returns a fresh, mutated clone of $this/self while preserving original
     * object immutability.
     *
     * In truth table
     */
    case StateTransformer = 'state_transformer';

    /**
     * In-place fluent transformation.
     *
     * Mutates $this/self in place and returns the same $this/self instance reference.
     *
     * In truth table
     */
    case StateTransformerThis = 'state_transformer_this';

    /** Domain-Driven Design */

    /**
     * Instantiation.
     *
     * Constructs and yields a newly created instance of a target object from parameters.
     *
     * Could be confused with Query.
     *
     * In truth table
     */
    case Factory = 'factory';

    /**
     * Also known as Static Initializer: Class-bound static Factory.
     *
     * Static Method on a class that encapsulates domain-validated instantiation
     * of `self` (class being defined) or `static` (self or subclasses).
     *
     * In truth table
     */
    case NamedConstructor = 'named_constructor';

    /**
     * Dedicated execution unit.
     *
     * Processes a single Command, Query, Event, or HTTP Request to completion.
     */
    case Handler = 'handler';

    /** Invariants, evaluation, and safety checks */

    /**
     * Fail-closed rule enforcement.
     *
     * Enforces mandatory system invariants. Returns : never or throws an exception
     * on failure, halting execution flow.
     *
     * In truth table
     */
    case Assertion = 'assertion';

    /**
     * Boolean evaluation.
     *
     * Side-effect free inspection that strictly returns a boolean (: bool) decision.
     *
     * In truth table
     */
    case Predicate = 'predicate';

    /**
     * Also known as Gate Check: Non-terminating safety check.
     *
     * Evaluates a rule or rate limit and returns a boolean status annotated with
     * #[\NoDiscard] so the caller must handle the result without halting execution.
     */
    case Check = 'check'; // Predicate with #[\NoDiscard('error message')]

    /** Functional core and architectural patterns */

    /**
     * Deterministic & side-effect free.
     *
     * Computes output strictly from explicit arguments without reading or mutating
     * $this, static properties, or external state.
     */
    case StrictPure = 'StrictPure';

    /**
     * Pipeline interceptor.
     *
     * Processes or decorates execution context along a chain, delegating
     * or halting response flow.
     */
    case Middleware = 'middleware';

    /**
     * Decision evaluation.
     *
     * Read-only component that evaluates attributes, context, and subject to
     * return a vote or permission score.
     *
     * In truth table
     */
    case Voter = 'voter';

    /**
     * Determines whether an attribute attributeName (FQCN or short attributeName)
     * matches a valid subtype.
     */
    public static function isValidAttribute(string $attributeName): bool
    {
        return self::fromAttributeName($attributeName) !== self::NotFound;
    }

    /**
     * Resolves an attribute attributeName to a CallableSubtype enum case.
     */
    public static function fromAttributeName(string $attributeName): self
    {
        /** @var array<string, self>|null $lookupMap */
        static $lookupMap = null;

        if ($lookupMap === null) {
            $map = [];
            foreach (self::cases() as $case) {
                if ($case === self::NotFound) {
                    continue;
                }
                // Index by PascalCase case attributeName (e.g. 'Command',
                // 'CommandWithStatus')
                $map[$case->name] = $case;
                // Index by value string (e.g. 'command', 'command_with_status')
                $map[$case->value] = $case;
            }
            $map['Accessor'] = self::Query;
            $map['accessor'] = self::Query;
            $map['Pure'] = self::StrictPure;
            $map['pure'] = self::StrictPure;
            $lookupMap = $map;
        }

        // Extract short class attributeName if a FQCN was provided
        $shortName = str_contains($attributeName, '\\')
            ? substr($attributeName, strrpos($attributeName, '\\') + 1)
            : $attributeName;

        return $lookupMap[$shortName]
            ?? $lookupMap[lcfirst($shortName)]
            ?? self::NotFound;
    }
}
