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

## Install contribution policy and templates

The [contribution guide](.github/contributing.md) defines the cumulative
triage rubric and change classification. This repository retains existing
labels while adding the apps categories. Labels are live GitHub metadata, not
files; use the guide's
[label-cloning procedure](.github/contributing.md#labels-in-consuming-repositories)
to add them to a consuming repository.

The reusable-file inventory below records each command's package source and
consumer destination. They are the same relative path, rooted respectively
at the installed package and the consuming project's current directory.

| `efqs` command | Source and destination |
| --- | --- |
| `docs:contributing` | `.github/contributing.md` |
| `docs:convention-workflow` | `docs/convention-workflow.md` |
| `templates:bug` | `.github/ISSUE_TEMPLATE/Bug_report.md` |
| `templates:feature` | `.github/ISSUE_TEMPLATE/Feature_request.md` |
| `templates:deprecation` | `.github/ISSUE_TEMPLATE/Deprecation_notice_or_request.md` |
| `templates:convention` | `.github/ISSUE_TEMPLATE/Convention_request.md` |
| `templates:pull-request` | `.github/pull_request_template.md` |

Run individual commands from the consuming project root:

```sh
./vendor/bin/efqs docs:contributing
./vendor/bin/efqs templates:bug
./vendor/bin/efqs templates:pull-request
```

For a human-friendly Composer entry point, add the commands needed by the
project to its existing scripts:

```json
{
  "scripts": {
    "efqs:docs:contributing": "efqs docs:contributing",
    "efqs:templates:bug": "efqs templates:bug",
    "efqs:templates:pull-request": "efqs templates:pull-request"
  }
}
```

All copy commands retain the same no-overwrite behavior as `docs:agents`.
They create directories when needed but do not install labels or copy linked
files automatically. Keep the shared agent rules, project context, security
policy, code of conduct, and relevant instructions available; adapt links and
commands to the consuming project. Convention templates also refer to the
convention workflow. Review existing GitHub template locations before copying:
the default PR template is `.github/pull_request_template.md`, not an
additional conflicting default.

## Contributing and choosing work

Use the [issue-triage procedure](.github/contributing.md#procedure) when
evaluating the backlog. Labels contribute qualitative impact, while current
evidence and dependencies establish readiness. A recommendation does not
authorize implementation or issue changes. The
[template instruction](.github/instructions/github-templates.instructions.md)
governs template maintenance.

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

- [Class stringability](.github/instructions/php-class-stringability.instructions.md):
  directly stringable classes implement `Eightfold\Interfaces\ToString`, which
  also requires `toString(): string`. The direct-declaration PHPCS and PHPStan
  checks are implemented but not yet enabled.
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

- **Class finality and inheritance:** `ClassesShouldBeFinal` and
  `ClassesShouldNotBeSubclass` recognize `Eightfold\Attributes\Classes\BaseClass`
  and `Eightfold\Attributes\Classes\Subclass` as their respective advisory
  opt-outs. The corresponding `Must` rules do not accept those opt-outs.
  Custom PHPStan rules are currently disabled in `extension.neon`.
- Data Objects are PHP classes

## Attribute inference

`CallableSubtype` supports name lookups, but the unfinished `fromFlags()`
sketch is not part of the production API. The
[inference prototype](experimental/Analysis/CallablesMustBeTyped/README.md) is
non-autoloaded and unsupported.
