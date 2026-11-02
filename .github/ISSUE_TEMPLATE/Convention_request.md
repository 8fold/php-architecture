---
name: Convention request
about: Track a coding convention and its development needs
---

<!-- Process: docs/convention-workflow.md -->

## Summary

<!-- State the convention with one compliant and one noncompliant example. -->

```php
// Noncompliant

// Compliant
```

## Proposed name

<!-- Sniff and rule name per .github/instructions/analysis-naming.instructions.md. -->

## Tasks

<!-- Keep each recommended layer. For a layer that does not apply or is not recommended, replace its task with the reason. -->

- [ ] **Agent instruction:** a `.github/instructions/*.instructions.md` file, new or existing. Must pass `composer check:instructions`.
- [ ] **PHPCS sniff:** follow `.github/instructions/phpcs-sniff-development.instructions.md`.
- [ ] **PHPStan rule:** follow the sniff stages and approval gates.
- [ ] **Trait:** add or update a trait when shared implementation is needed.
- [ ] **Interface:** add or update an interface when a shared contract is needed.
- [ ] **Attribute:** add or update a PHP attribute when declarative metadata is needed.

## Open questions

<!-- Accuracy, overlap, existing code, and behavior (docs/convention-workflow.md#layers). -->

## Policy status and acceptance criteria

<!-- Record the proposal or adoption, its impact, and the observable outcomes that establish success. Do not infer policy adoption from authorship. -->

## Out of scope

- Rewriting existing code.

## Related

<!-- Blockers and related issues. -->
