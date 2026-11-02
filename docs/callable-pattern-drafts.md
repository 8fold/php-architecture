# Draft callable pattern examples

These illustrative fragments were kept separate from the autoloaded attributes.
They depend on application-provided types such as `SecurityContext`,
`MiddlewareInterface`, and `RequestHandlerInterface`; they are not package APIs.

```php
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
            if (str_contains($value, '@') === false) {
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
```
