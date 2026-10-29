<?php

declare(strict_types=1);

namespace Eightfold\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class Predicate
{
    public static function behavior(): string
    {
        return <<<behavior
        Performs a deterministic transformation dependent solely on its input parameters.
        behavior;
    }
    
    public static function definingInvariants(): string
    {
        return <<<qualities
        1. Determinism: The same input arguments ALWAYS produce the exact same output value.
        2. Zero Side Effects: Reads no global state, performs no I/O, and mutates no external variables or arguments.
        3. Referential Transparency: The function call can be safely replaced by its evaluation result.
        4. Functional Composability: Directly compatible with PHP 8.5's Pipe Operator (|>) and callables.
        qualities>>>;
    }
    
    public static function recommendedAlternatives(): string
    {
        return '';
    }
    
    public static function example(): string
    {
        return <<<example
        function sanitizeCurrencyCode(string $rawCode): string
        {
            return strtoupper(trim($rawCode));
        }

        function formatAmount(float $amount): string
        {
            return sprintf('$%.2f USD', $amount);
        }
        example;
    }
}

/**
 * Comprehensive Architectural Guide: Named Function & Method Types in PHP 8.5
 *
 * Just as classes are categorized into architectural roles (such as Data Transfer
 * Objects (DTOs), Repositories, or Controllers), functions and methods are also
 * categorized into well-defined, named types based on their behavior, return semantics,
 * side effects, and structural role within an application architecture.
 *
 * Each type is defined by its immutable qualities--the fundamental invariants regarding
 * control flow, state mutability, side-effects, and architectural responsibility.
 */

namespace Architecture\FunctionTypes;

use Attribute;
use Exception;
use InvalidArgumentException;
use RuntimeException;

// ============================================================================
// 1. BEHAVIORAL & COMMAND-QUERY SEPARATION (CQS) TYPES
// ============================================================================

/**
 * Demonstrates Command, Query, and Predicate method types within a domain service.
 */
class AccountService
{
    private float $balance = 1000.0;

    /** @var array<int, string> */
    private array $auditLog = [];

// ============================================================================
// 2. OBJECT LIFECYCLE & IMMUTABILITY TYPES
// ============================================================================

/**
 * TYPE: Immutable Value Object with Wither Methods & Factory Methods
 */
final readonly class CurrencyAmount
{
    /**
     * TYPE: Factory Method (Static Factory)
     *
     * BEHAVIOR: Encapsulates instantiation logic, parameter normalization, or driver selection.
     *
     * IMMUTABLE QUALITIES (DEFINING INVARIANTS):
     * 1. Construction Abstraction: Decouples instantiation details from direct constructor invocation.
     * 2. Expressive Entry Point: Conveys domain intent via descriptive names (e.g., `fromCents()`, `fromArray()`).
     * 3. Instance Return Guarantee: Always yields a fully initialized object or driver subclass.
     */
    public static function fromCents(int $cents, string $currency = 'USD'): self
    {
        return new self($cents / 100.0, $currency);
    }

    /**
     * TYPE: Wither ("With-er") Method
     *
     * BEHAVIOR: Creates a modified copy of an immutable object while leaving `$this` untouched.
     *
     * IMMUTABLE QUALITIES (DEFINING INVARIANTS):
     * 1. Immutability Preservation: Never mutates the original object instance ($this).
     * 2. Fresh Instance Yield: Always returns a new instance (`self`/`static`) containing the updated state.
     * 3. Native PHP 8.5 Clone with Syntax: Leverages `clone($this, ['prop' => $value])`.
     */
    public function withAmount(float $newAmount): self
    {
        // PHP 8.5 native clone with inline property modification
        return clone($this, [
            'amount' => $newAmount,
        ]);
    }
}


// ============================================================================
// 3. ARCHITECTURAL & WORKFLOW TYPES
// ============================================================================

/**
 * Immutable security request context
 */
readonly class SecurityContext
{
    public function __construct(
        public string $userId,
        public array $roles,
        public bool $isMfaVerified = false
    ) {}
}

/**
 * Interface for PSR-15-style Handlers
 */
interface RequestHandlerInterface
{
    public function handle(SecurityContext $context): string;
}

/**
 * Interface for PSR-15-style Middleware
 */
interface MiddlewareInterface
{
    public function process(SecurityContext $context, RequestHandlerInterface $handler): string;
}

/**
 * TYPE: Guard / Assertion Method
 *
 * BEHAVIOR: Enforces security assertions or system safety rules, failing closed if unmet.
 *
 * IMMUTABLE QUALITIES (DEFINING INVARIANTS):
 * 1. Fail-Closed Execution: Throws an exception or returns a failure status when invariants fail.
 * 2. Mandatory Consumability (#[\NoDiscard]): Annotated with PHP 8.5 `#[\NoDiscard]` so callers
 *    cannot silently ignore critical safety or authorization check return values.
 */
class AuthorizationGuard
{
    #[\NoDiscard("Security verification result must be evaluated or explicitly cast to (void)")]
    public function verifyAccess(SecurityContext $context, string $requiredRole): bool
    {
        if (!$context->isMfaVerified) {
            return false;
        }

        return in_array($requiredRole, $context->roles, true);
    }
}

/**
 * TYPE: Middleware (Interceptor) Method
 *
 * BEHAVIOR: Intercepts workflow execution to perform pre- or post-processing or short-circuiting.
 *
 * IMMUTABLE QUALITIES (DEFINING INVARIANTS):
 * 1. Delegation Pattern: Accepts the context AND the downstream `$handler`, delegating control.
 * 2. Layered Pipeline Stage: Can inspect, modify, or halt request/response execution.
 */
class MfaVerificationMiddleware implements MiddlewareInterface
{
    public function process(SecurityContext $context, RequestHandlerInterface $handler): string
    {
        if (!$context->isMfaVerified) {
            return "403 Forbidden: Multi-Factor Authentication Required";
        }

        return $handler->handle($context);
    }
}

/**
 * TYPE: Handler Method
 *
 * BEHAVIOR: Terminal execution entry point that processes context and returns a final response.
 *
 * IMMUTABLE QUALITIES (DEFINING INVARIANTS):
 * 1. Pipeline Terminal: Does not delegate downstream; produces the final domain result or HTTP response.
 * 2. Single Responsibility: Dedicated to fulfilling a specific request or message contract.
 */
class AccountDashboardHandler implements RequestHandlerInterface
{
    public function handle(SecurityContext $context): string
    {
        return "200 OK: Welcome to user dashboard for " . $context->userId;
    }
}

/**
 * TYPE: Voter Method
 *
 * BEHAVIOR: Evaluates domain permissions and returns a structured decision vote.
 *
 * IMMUTABLE QUALITIES (DEFINING INVARIANTS):
 * 1. Decoupled Access Decision: Evaluates subject, resource, and attribute attributes without mutating them.
 * 2. Standard Decision Enum/Constant: Returns an explicit vote (e.g., ACCESS_GRANTED, ACCESS_DENIED, ACCESS_ABSTAIN).
 */
enum Vote: string
{
    case Granted = 'ACCESS_GRANTED';
    case Denied = 'ACCESS_DENIED';
    case Abstain = 'ACCESS_ABSTAIN';
}

class AdminRoleVoter
{
    public function vote(SecurityContext $context, string $attribute): Vote
    {
        if ($attribute !== 'ROLE_ADMIN') {
            return Vote::Abstain;
        }

        return in_array('admin', $context->roles, true) ? Vote::Granted : Vote::Denied;
    }
}


// ============================================================================
// 4. ENGINE & ACCESSOR TYPES
// ============================================================================

/**
 * TYPE: Property Hooks (Get & Set Accessors)
 *
 * BEHAVIOR: Virtual or backed accessors declared directly on object properties.
 *
 * IMMUTABLE QUALITIES (DEFINING INVARIANTS):
 * 1. In-Situ Access Encapsulation: Intercepts reads (`get`) and writes (`set`) natively without generic magic methods.
 * 2. Fine-Grained Property Invariants: Enables inline validation, formatting, or virtual property computation.
 */
class UserProfile
{
    // Backed property with set hook validation
    public string $email = '' {
        set(string $value) {
            if (!str_contains($value, '@')) {
                throw new InvalidArgumentException("Invalid email address format: {$value}");
            }
            $this->email = strtolower(trim($value));
        }
    }

    public string $firstName = '';
    public string $lastName = '';

    // Virtual property hook (no backing storage)
    public string $fullName {
        get => sprintf("%s %s", $this->firstName, $this->lastName);
    }
}


// ============================================================================
// DEMONSTRATION & VERIFICATION HARNESS
// ============================================================================

/**
 * Executes a demonstration of each function and method type.
 */
function runDemonstration(): void
{
    echo "=== PHP 8.5 FUNCTION & METHOD TYPES DEMONSTRATION ===\n\n";

    // 1. Command & Query & Predicate
    echo "[1] Command & Query & Predicate Methods:\n";
    $account = new AccountService();
    echo "Initial Balance (Query): " . formatAmount($account->getBalance()) . "\n";
    echo "Has $500? (Predicate): " . ($account->hasSufficientBalance(500) ? 'YES' : 'NO') . "\n";
    $account->withdrawFunds(250.0); // Command (Mutator)
    echo "After Withdrawal (Command executed), Balance (Query): " . formatAmount($account->getBalance()) . "\n\n";

    // 2. Pure Function & Pipe Operator (|>)
    echo "[2] Pure Function & Pipe Operator (|>):\n";
    $rawInput = "  usd  ";
    $formattedCurrency = $rawInput
        |> sanitizeCurrencyCode(...)
        |> (static fn(string $code) => "Currency: " . $code);
    echo "Piped Result: {$formattedCurrency}\n\n";

    // 3. Factory & Wither Methods
    echo "[3] Factory & Wither (\"With-er\") Methods:\n";
    $initialAmount = CurrencyAmount::fromCents(4999, 'USD'); // Factory
    echo "Initial Instance: {$initialAmount->amount} {$initialAmount->currency}\n";
    $updatedAmount = $initialAmount->withAmount(79.99); // Wither
    echo "Updated Instance: {$updatedAmount->amount} {$updatedAmount->currency}\n";
    echo "Original Unchanged: {$initialAmount->amount} {$initialAmount->currency}\n\n";

    // 4. Guard Assertion & #[\NoDiscard] & (void) cast
    echo "[4] Guard Assertion Method & #[\NoDiscard]:\n";
    $guard = new AuthorizationGuard();
    $context = new SecurityContext('usr_101', ['admin', 'manager'], isMfaVerified: true);
    
    // Explicitly using the return value prevents #[\NoDiscard] warning
    $isAllowed = $guard->verifyAccess($context, 'admin');
    echo "Access Verified: " . ($isAllowed ? 'ALLOWED' : 'DENIED') . "\n";

    // If intentionally ignoring, PHP 8.5 (void) cast suppresses warning
    (void) $guard->verifyAccess($context, 'manager');
    echo "Used (void) cast to explicitly ignore #[\NoDiscard] check without warning.\n\n";

    // 5. Middleware, Handler, and Voter
    echo "[5] Middleware, Handler, & Voter Methods:\n";
    $handler = new AccountDashboardHandler();
    $middleware = new MfaVerificationMiddleware();
    $voter = new AdminRoleVoter();

    $voteResult = $voter->vote($context, 'ROLE_ADMIN');
    echo "Voter Decision: {$voteResult->value}\n";
    $pipelineResult = $middleware->process($context, $handler);
    echo "Pipeline Result: {$pipelineResult}\n\n";

    // 6. Property Hooks
    echo "[6] Property Hooks (Get/Set):\n";
    $profile = new UserProfile();
    $profile->email = "  ALICE@EXAMPLE.COM  "; // Set hook validates and normalizes
    $profile->firstName = "Alice";
    $profile->lastName = "Smith";
    echo "Normalized Email (Set Hook): {$profile->email}\n";
    echo "Full Name (Virtual Get Hook): {$profile->fullName}\n\n";

    echo "=== DEMONSTRATION COMPLETED SUCCESSFULLY ===\n";
}

// Execute demonstration
runDemonstration();
