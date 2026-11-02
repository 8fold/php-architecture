# Convention workflow

This workflow turns a requested coding convention into tracked work. It starts
when the user asks for a new agent instruction, for example, "we need an agent
instruction to use explicit boolean comparisons."

[#31](https://github.com/8fold/php-architecture/issues/31) is the first
example.

## Steps

1. **Clarify the convention.** Restate it with one compliant and one
   noncompliant example. Ask when the intent is ambiguous.
2. **Assess each layer.** Decide whether each layer applies and whether it is
   recommended ([Layers](#layers)). Present the assessment, including
   alternatives and reasons to skip a layer.
3. **Create one issue.** Track all layers in a single issue from the
   [convention request template](../.github/ISSUE_TEMPLATE/Convention_request.md),
   keeping its sections. Do not create separate issues per layer unless the
   user asks.
4. **Stop.** Creating the issue does not authorize implementation. Each layer
   is implemented later, on request, through its own process.

## Layers

| Layer | When it applies | Implementation process |
| --- | --- | --- |
| Agent instruction | Always. | A `.github/instructions/*.instructions.md` file, new or existing; must pass `composer check:instructions`. |
| PHPCS sniff | The convention is detectable from tokens alone, without types. | [PHPCS sniff development](../.github/instructions/phpcs-sniff-development.instructions.md). |
| PHPStan rule | Detection needs types, symbols, or semantic analysis. | No documented process yet; follow the sniff stages (fixtures, tests, implementation, activation) with the same approval gates. |

Recommend a sniff or rule only when its benefit outweighs its cost. Consider:

- **Accuracy.** Whether the check can be precise enough to avoid false
  positives. PHPCS cannot see types; PHPStan can.
- **Overlap.** Whether one tool already covers the convention, including
  existing third-party rules.
- **Existing code.** How much existing code would be flagged. Guidance is not
  permission to refactor
  ([Navigation, tools, and validation](../AGENTS.md#navigation-tools-and-validation)
  rule 7); consider `Should` severity or a baseline.
- **Behavior.** Whether a suggested fix preserves behavior in every case.
