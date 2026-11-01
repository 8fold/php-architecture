---
applyTo: "src/PHPStan/**/*.php,src/PHPCS/**/*.php,tests/PHPStan/**/*.php,tests/PHPCS/**/*.php"
---

# Analysis naming

## Naming

Name sniffs and PHPStan rules as the rule they enforce, using an
[RFC 2119](https://www.rfc-editor.org/rfc/rfc2119) keyword in PascalCase:
`Must`, `MustNot`, `Should`, `ShouldNot`, or `May`. Examples:
`InstanceMethodsMustNotBeAccessedViaThis`, `ClassMustBeFinal`.

Put the PHPCS rule body under `src/PHPCS/Sniffs/<Category>/<Rule>.php`.
Register it through a shell in the `Eightfold` standard that delegates to the
body: `src/PHPCS/Standard/Eightfold/Sniffs/<Category>/<Rule>Sniff.php`.
PHPCS discovers `Sniff.php` files in a `Sniffs/` directory and uses the
registered class's `<Standard>\Sniffs\<Category>` namespace and `Sniff`
suffix to identify it.

Name PHPCS fixtures and tests after the rule, adding `Compliant`,
`Noncompliant`, or `Test`.
