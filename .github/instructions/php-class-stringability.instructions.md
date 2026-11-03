---
applyTo: "src/**/*.php,experimental/Analysis/**/*.php,tests/**/*.php"
---

# Class stringability

- A class that directly implements `\Stringable` or declares the `__toString()`
  magic method MUST directly implement `Eightfold\Interfaces\ToString`.
- `ToString` extends `\Stringable` and requires `toString(): string`; the class
  must also fulfill the native `__toString(): string` contract. Declaring both
  interfaces is permitted but redundant.
- The initial automated checks examine declarations on the class itself.
  Inherited or indirect relationships and trait-provided stringability require
  separate review. Traits, interfaces, and enums are not targets.
- Keep intentionally noncompliant analysis fixtures unchanged.
