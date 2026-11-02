# 8fold Architecture for PHP

## Install the shared agent rules, context, and Graphify guides

Add `8fold/php-architecture` to the consuming project's Composer dependencies
and install them. From the consuming project's root, copy the shared rules and
the project context and Graphify guides:

```sh
./vendor/bin/efqs docs:agents
./vendor/bin/efqs docs:context
./vendor/bin/efqs docs:graphify-setup
./vendor/bin/efqs docs:graphify-workflow
```

The commands copy `AGENTS.md`, `docs/agent-context.md`,
`docs/graphify-setup.md`, and `docs/graphify-workflow.md`, respectively,
creating destination directories if needed. Each command leaves an identical
destination unchanged and refuses to overwrite a different file. Copying the
guides does not install Graphify, configure hooks, or create a graph.

Composer makes `efqs` available in its configured `bin-dir`, which defaults to
`vendor/bin`. To expose the commands as Composer scripts, add these entries to
the consuming project's existing `composer.json` scripts:

```json
{
  "scripts": {
    "efqs:docs:agents": "efqs docs:agents",
    "efqs:docs:context": "efqs docs:context",
    "efqs:docs:graphify-setup": "efqs docs:graphify-setup",
    "efqs:docs:graphify-workflow": "efqs docs:graphify-workflow"
  }
}
```

The copied context and guides are starting points, not consuming-project facts.
Adapt project-specific setup and commands before relying on them.

## Refresh code in this repository's knowledge graph

From the repository root:

```sh
composer graph:code
```

This refreshes code only; documentation still needs the
[Graphify skill's semantic refresh](docs/graphify-workflow.md#refresh). The
[agent context](docs/agent-context.md#build-test-and-lint-commands) describes
the command's requirements and its code-only alias.

## PHP conventions (strict)

- [Explicit boolean expressions](.github/instructions/php-boolean-expressions.instructions.md):
  prefer positive conditions or strict comparisons to negating a boolean
  expression with `!`. The type-aware `BooleanNegationMustBeExplicit` PHPStan
  rule is implemented but disabled alongside the other custom rules.
  See the [enforcement boundaries](docs/agent-context.md#enforcement-boundaries)
  for its scope and current activation state.
- **Method scope:** [Guidance](.github/instructions/php-class-use.instructions.md)
  distinguishes `self::`, `parent::`, and `$this->`. The registered PHPCS sniff
  checks instance method calls via `$this->`; it does not enforce every scope
  convention.
- **Data object and record purity:** No PHPStan purity rules are currently
  shipped.


## PHP conventions (default with options)

- **Class finality and inheritance:** `ClassShouldBeFinal` and
  `ClassShouldNotBeSubclass` recognize `Eightfold\Attributes\Classes\BaseClass`
  and `Eightfold\Attributes\Classes\Subclass` as their respective advisory
  opt-outs. The corresponding `Must` rules do not accept those opt-outs.
  Custom PHPStan rules are currently disabled in `extension.neon`.
- Data Objects are PHP classes

## Attribute inference

`CallableSubtype` supports name lookups, but the unfinished `fromFlags()`
sketch is not part of the production API. The
[inference prototype](experimental/attribute-inference/README.md) is
non-autoloaded and unsupported.
