# Convention workflow

This workflow develops a requested coding convention from concrete PHP
fixtures. It starts when the user asks for a convention or agent instruction,
or when an issue requests one. For example, "we need an agent instruction to
use explicit boolean comparisons."

Start with the closest reasonable approximation of compliant and noncompliant
code, then refine it through review. Exhaustive agreement on names, exceptions,
and ordering is not a prerequisite for drafting fixtures.

[#31](https://github.com/8fold/php-architecture/issues/31) is an example
convention request.

## Steps

1. **Create a fixture.** Draft syntactically valid compliant and intentionally
   noncompliant fixtures from the request. Describe the expected diagnostics,
   cases that must not be reported, and provisional assumptions. Use the
   applicable analyzer's fixture directory. A request to develop the
   convention authorizes opening a pull request with the fixture-only draft
   for review, without the separate approval normally required by the
   [PR gate](../AGENTS.md#human-gates-and-git-workflow). It does not authorize
   tests, implementation, or activation.
2. **Fixture approval.** Present the fixtures and iterate with the user until
   they represent the intended convention. Resolve scope questions through
   concrete examples rather than requiring a complete abstract specification
   first. Stop for explicit approval before writing tests.
3. **Tests.** Write isolated tests for the agreed analyzer behavior and
   demonstrate the intended failing assertions before implementation. Follow
   the applicable [layer's process](#layers) for diagnostic assertions and
   setup requirements, and
   [Test scope and evidence](../.github/instructions/phpunit-test-definitions.instructions.md#test-scope-and-evidence)
   for test boundaries, input validity, and red-test evidence.
4. **Test approval.** Review the assertions and observed results with the user.
   Iterate and obtain explicit approval before implementation.
5. **Implementation.** Implement the approved behavior until the approved tests
   pass. Do not weaken assertions or repair intentional fixture violations
   merely to obtain passing tests.
6. **Activation approval.** Review the implementation and results with the
   user. Obtain separate explicit approval before enabling the convention in
   repository analysis. Follow the layer's integration and validation process;
   report unrelated baseline violations without expanding scope.

The detailed analyzer stages are defined in
[PHPCS sniff development](../.github/instructions/phpcs-sniff-development.instructions.md).
Fixture approval authorizes tests, not implementation; test approval does not
authorize activation.

## Tracking and layer assessment

As the fixtures are refined, assess each [layer](#layers), including
alternatives and reasons to skip a layer. If a layer has no automated test or
activation step, record why that stage does not apply.

Track all layers in one issue using the
[convention request template](../.github/ISSUE_TEMPLATE/Convention_request.md),
keeping its sections. Update an existing issue rather than creating a
duplicate. Issue creation is not a prerequisite for drafting fixtures and
does not authorize implementation. Do not create separate issues per layer
unless the user asks.

## Layers

| Layer | When it applies | Implementation process |
| --- | --- | --- |
| Agent instruction | Always. | A `.github/instructions/*.instructions.md` file, new or existing; must pass `composer check:instructions`. |
| PHPCS sniff | The convention is detectable from tokens alone, without types. | [PHPCS sniff development](../.github/instructions/phpcs-sniff-development.instructions.md). |
| PHPStan rule | Detection needs types, symbols, or semantic analysis. | No documented process yet; follow the sniff stages (fixtures, tests, implementation, activation) with the same approval gates. |
| Trait | A shared implementation would support the convention. | Add or update a PHP trait. |
| Interface | A shared contract would support the convention. | Add or update a PHP interface. |

Recommend optional work only when its benefit outweighs its cost. Consider:

- **Accuracy.** Whether the check can be precise enough to avoid false
  positives. PHPCS cannot see types; PHPStan can.
- **Overlap.** Whether one tool already covers the convention, including
  existing third-party rules.
- **Existing code.** How much existing code would be flagged. Guidance is not
  permission to refactor
  ([Navigation, tools, and validation](../AGENTS.md#navigation-tools-and-validation)
  rule 7); consider `Should` severity or a baseline.
- **Behavior.** Whether a suggested fix preserves behavior in every case.
