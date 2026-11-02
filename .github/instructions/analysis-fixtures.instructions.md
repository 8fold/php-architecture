---
applyTo: "tests/PHPStan/Fixtures/**,tests/PHPCS/Fixtures/**"
---

# Analysis fixtures

Fixtures mirror the convention folders in `src/Analysis/`:

```text
tests/<Analyzer>/Fixtures/<Convention>/
├── Compliant.php     required
├── Noncompliant.php  required
└── index.php         optional
```

- `<Analyzer>` is `PHPStan` or `PHPCS`; `<Convention>` matches the folder
  under `src/Analysis/`. Each analyzer implementation is standalone, so each
  analyzer has its own fixtures, even for a shared convention.
- Every convention folder MUST contain at least `Compliant.php` and
  `Noncompliant.php`. Add as many further fixtures as needed, named for the
  scenario they cover, for example `NonBoolean.php`.
- Use `index.php` for file-level code without a class declaration, such as
  entry points and scripts, when `Compliant.php` and `Noncompliant.php` do
  not cover that case.
- Declare classes in a namespace matching the folder, for example
  `Eightfold\Tests\PHPStan\Fixtures\ClassesMustBeFinal`, and name each class
  after its file. Tests import fixture classes with an alias combining the
  convention and file names, such as `ClassesMustBeFinalCompliant`.
- Describe the expected diagnostics in a docblock or comment, including cases
  that must not be reported. Noncompliant fixtures stay intentionally
  noncompliant.
- Reference real symbols, from PHP, Composer dependencies, or this
  repository; `composer check:analyze` analyzes PHPCS fixtures.
- Tests may reuse a fixture from another convention folder when it covers the
  needed case.
