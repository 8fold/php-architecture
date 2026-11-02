---
applyTo: "src/Analysis/**/*.php,src/PHPCS/**/*.php,experimental/Analysis/**/*.php,tests/PHPStan/**/*.php,tests/PHPCS/**/*.php"
---

# Analysis naming

- PHP_CodeSniffer (PHPCS) sniffs and PHPStan rules live under their convention
  folder in `src/Analysis/`, using `PHPCSSniff.php` and `PHPStanRule.php`.
- Name sniffs and rules after the rule they enforce, using an
  [RFC 2119](https://www.rfc-editor.org/rfc/rfc2119) term where applicable, or
  `Can` for the preferred optional level. Use PascalCase: `Must`, `MustNot`,
  `Should`, `ShouldNot`, or `Can`. Examples:
  `InstanceMethodsMustNotBeAccessedViaThis`, `ClassesMustBeFinal`.
- Start conventions applying to classes with `Classes`, not `Class`.
- PHPStan identifiers use `eightfold.` followed by the convention folder name
  with its first character lowercased; do not derive them from the shared
  `PHPStanRule` class name.
- Name tests and fixtures after the rule, adding `Compliant`, `Noncompliant`,
  or `Test`. 
- Experimental analysis files mirror this structure under
  `experimental/Analysis/` and are not Composer-autoloaded.
