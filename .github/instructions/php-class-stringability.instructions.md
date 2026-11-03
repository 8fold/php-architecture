---
applyTo: "src/**/*.php,experimental/Analysis/**/*.php,tests/**/*.php"
---

# Class stringability

- A class that implements `\Stringable` or provides `__toString()`, including
  through a parent, interface, or trait, MUST implement
  `Eightfold\Interfaces\ToString` directly or indirectly.
- `ToString` extends `\Stringable` and requires `toString(): string`; the class
  must also fulfill the native `__toString(): string` contract. Declaring both
  interfaces is permitted but redundant.
- The PHPStan rule checks effective class stringability and suite-interface
  compliance. Traits, interfaces, and enums are not targets.
- Keep intentionally noncompliant analysis fixtures unchanged.
