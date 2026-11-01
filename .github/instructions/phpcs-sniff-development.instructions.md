---
applyTo: "src/PHPCodeSniffer/**/*.php,tests/PHPCS/**/*.php,phpcs.xml"
---

# PHPCS sniff development

Follow these stages in order. Stop for explicit user approval at stages 2, 4,
and 6. Fixture approval authorizes tests; test approval authorizes
implementation; activation approval authorizes ruleset integration. An
approval does not satisfy a later gate.

1. **Fixtures.** Create syntactically valid compliant and intentionally
   noncompliant fixtures under `tests/PHPCS/Fixtures/`. Describe the expected
   diagnostics and include cases that must not be reported.
2. **Fixture approval.** Review the fixtures with the user. Iterate until the
   user agrees that they accurately represent the rule. Do not write the tests
   before approval.
3. **Tests.** Analyze the fixtures explicitly with the proposed sniff, isolated
   from unrelated rules and the repository style sweep's fixture exclusion.
   Assert no diagnostics for compliant fixtures and only the agreed diagnostic
   codes, locations, severity, and counts for noncompliant fixtures.
   Demonstrate the intended failing assertions before implementation; syntax,
   loading, or test-runner errors are setup failures, not valid red tests.
4. **Test approval.** Review the assertions and observed results with the user.
   Iterate until the user agrees that the tests verify the intended behavior.
   Do not implement the sniff before approval.
5. **Implementation.** Implement the sniff until all approved tests pass.
   Noncompliant fixtures remain intentionally noncompliant; their tests pass
   by asserting the expected diagnostics. Do not weaken approved assertions or
   change fixtures merely to obtain passing tests.
6. **Activation approval.** Review the sniff and its results with the user.
   After explicit approval, register it in the PHPCS ruleset (`phpcs.xml`),
   not PHPStan's `extension.neon`. Run `composer check:style`, review existing
   violations before changing code, and fix approved, in-scope errors until
   passing. Report unrelated baseline failures rather than expanding scope
   silently. Do not make automatic behavior-changing rewrites.

`tests/PHPCS/Fixtures/` is excluded from the repository style sweep, not from
explicit sniff tests. Never repair intentional violations to make the style
sweep pass.
