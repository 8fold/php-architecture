---
applyTo: "tests/**/*Test.php,experimental/Analysis/**/*Test.php"
---

- Each class:
    - extends a test case class directly or indirectly.
- Each test method:
    - names describes behavior being tested.
    - names use lowercase words and spaces replaced by an underscore (_); snake_case.
    - is identified using PHPUnit's `#[Test]` attribute, not naming convention.
    - is a member of one or more groups using PHPUnit's `#[Group('name')]` attribute.

## Test scope and evidence

- Test behavior implemented by this package, not mechanisms implemented by
  third-party libraries. Use third-party tools to exercise convention detection,
  but do not reproduce their internal tests.
- Convention tests focus on detection correctness: compliant inputs produce no
  diagnostics, and noncompliant inputs produce the agreed diagnostics. Do not add
  standalone tests that merely restate the identifier formula or assert
  PHPStan's error marker types or suppression mechanics. Package-maintenance
  policies belong in agent instructions and dedicated static analysis, not
  repeated metadata assertions in each convention's PHPUnit tests.
- Every test input, including configured stubs, must represent the case its
  assertions describe. A diagnostic assertion requires an input that actually
  violates the convention.
- A red test demonstrates the intended assertion failing. Missing rule classes,
  syntax errors, autoload failures, and test-runner errors are setup failures,
  not evidence that the assertion detects missing convention behavior.
