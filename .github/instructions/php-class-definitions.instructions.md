---
applyTo: "src/*.php,tests/*.php"
---

# Class Declaration & Finality Rules

## Class Finality

- **Default Stance**: Always declare concrete classes as `final`.
- **Permitted Override**: Apply `#[AllowInheritance(reason: '...')]` ONLY when class inheritance is required by framework design (e.g., Doctrine ORM proxies, base classes intended for extension).
- **Mandated Rationale**: Every `#[AllowInheritance]` usage MUST specify a non-empty `reason:` parameter explaining why extension is required.
- **Negative Constraint**: NEVER leave a concrete class non-final without the `#[AllowInheritance]` attribute.

## Verification

- Verify class finality and purity rules: `./vendor/bin/phpstan analyze`
