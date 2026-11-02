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

## Refresh this repository's knowledge graph

From the repository root:

```sh
composer graph       # code, then documentation
composer graph:code  # code only: fast, no Copilot credits
composer graph:docs  # documentation: launches a Copilot agent, uses credits
```

Each command prints `FAILED` and stops if the refresh did not complete. The
[agent context](docs/agent-context.md#build-test-and-lint-commands) describes
what each command checks and requires.

## PHP conventions (strict)

- **`MethodScopeConventionRule`**: Enforces strict `self::`, `parent::`, and `$this->` scoping conventions on method calls.
- **`DataObjectPurityRule`**: Enforces strict immutable, property-only rules for class instances.
- **`RecordPurityRule`**: Enforces strict immutable, property-only Data Object rules applied to database row representation.


## PHP conventions (default with options)

- **`ClassMustBeFinalRule`:** Enforces classes are declared `final` by default. Use the `AllowInheritance` to explicitly allow class extension.
- Data Objects are PHP classes
