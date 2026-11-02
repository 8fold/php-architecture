---
applyTo: "src/**/*.php,tests/**/*.php,cli/**/*.php,bin/*"
---

# Boolean expressions

- Boolean negation MUST be explicit. Prefer a positive condition when it
  expresses the same intent clearly. Otherwise compare a boolean expression
  with `=== false` or `=== true`, rather than using the unary `!` operator.

  ```php
  // Noncompliant: is_file() returns bool.
  if (!is_file($path)) {
      throw new RuntimeException('File not found.');
  }

  // Compliant.
  if (is_file($path) === false) {
      throw new RuntimeException('File not found.');
  }
  ```

- Preserve evaluation order, short-circuiting, and behavior. Parenthesize
  composite operands where needed: `!($value instanceof Example)` becomes
  `($value instanceof Example) === false`.
- Do not mechanically change `!$value` to `$value === false` when the operand
  is nullable, non-boolean, or of unknown type. Determine the intended empty,
  null, zero, or other condition first. For example, `!null` is `true`, but
  `null === false` is `false`. Report unresolved intent rather than guessing.
- `!=` and `!==` are comparison operators, not unary negation; this convention
  does not prohibit them. `empty()` and `isset()` return booleans, so compare
  their results explicitly when negating them.
- Keep intentionally noncompliant analysis fixtures unchanged; they document
  violations for their diagnostic tests.
