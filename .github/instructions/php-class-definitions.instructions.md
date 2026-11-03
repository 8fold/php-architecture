---
applyTo: "src/**/*.php,experimental/Analysis/**/*.php,tests/**/*.php"
---

# Class Declaration & Finality Rules

## Class Finality

- **Default Stance**: Always declare concrete classes as `final`.
- **Permitted Override**: Apply
  `#[\Eightfold\Attributes\Classes\BaseClass(rationale: '...')]` only when the
  class must be designed for extension (for example, framework proxies or
  required base classes).
- **Mandated Rationale**: Every `BaseClass` usage must specify a non-empty
  `rationale:` argument explaining why extension is required.
- **Negative Constraint**: Never leave a concrete class non-final without the
  `BaseClass` attribute.

## Required Subclasses

- Prefer composition to extending another class.
- When extending another class is required, apply
  `#[\Eightfold\Attributes\Classes\Subclass(rationale: '...')]` with a non-empty
  rationale explaining the requirement.
- `Subclass` describes extending another class; `BaseClass` describes allowing
  further extension. A required subclass should still be `final` unless it also
  needs to be extended.

## Verification

- Run `composer check:analyze` for PHPStan analysis.
- These attributes express architectural intent; documentation is not proof
  of enforcement. The advisory PHPStan rules recognize the corresponding
  attributes in isolated tests, but the class-finality rule registrations in
  `extension.neon` remain commented out. Do not treat these conventions as
  enforced by the repository's normal analyzer configuration.
