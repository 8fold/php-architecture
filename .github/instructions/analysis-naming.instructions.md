---
applyTo: "src/PHPStan/**/*.php,src/PHPCS/**/*.php,tests/PHPStan/**/*.php,tests/PHPCS/**/*.php"
---

# Analysis naming

- PHP_CodeSniffer (PHPCS) sniffs and PHPStan rules follow the same naming
  convention.
- Name sniffs and rules after the rule they enforce, using an
  [RFC 2119](https://www.rfc-editor.org/rfc/rfc2119) keyword in PascalCase:
  `Must`, `MustNot`, `Should`, `ShouldNot`, or `May`. Examples:
  `InstanceMethodsMustNotBeAccessedViaThis`, `ClassMustBeFinal`.
- Name tests and fixtures after the rule, adding `Compliant`, `Noncompliant`,
  or `Test`.
