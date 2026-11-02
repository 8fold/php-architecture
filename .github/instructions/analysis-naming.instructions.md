---
applyTo: "src/PHPStan/**/*.php,src/PHPCS/**/*.php,tests/PHPStan/**/*.php,tests/PHPCS/**/*.php"
---

# Analysis naming

## Naming

Name sniffs and PHPStan rules as the rule they enforce, using an
[RFC 2119](https://www.rfc-editor.org/rfc/rfc2119) keyword in PascalCase:
`Must`, `MustNot`, `Should`, `ShouldNot`, or `May`. Examples:
`InstanceMethodsMustNotBeAccessedViaThis`, `ClassMustBeFinal`.

Name PHPCS fixtures and tests after the rule, adding `Compliant`,
`Noncompliant`, or `Test`.
