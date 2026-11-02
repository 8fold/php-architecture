# 8fold Architecture for PHP

## Install the shared agent rules, context, and Graphify guide

Add `8fold/php-architecture` to the consuming project's Composer dependencies
and install them. From the consuming project's root, copy the shared rules and
the project context and setup guide:

```sh
./vendor/bin/efqs docs:agents
./vendor/bin/efqs docs:context
./vendor/bin/efqs docs:graphify
```

The commands copy `AGENTS.md`, `docs/agent-context.md`, and
`docs/graphify-workflow-setup.md`, respectively, creating destination directories
if needed. Each command leaves an identical destination unchanged and refuses
to overwrite a different file. Copying the guide does not install Graphify,
configure hooks, or create a graph.

Composer makes `efqs` available in its configured `bin-dir`, which defaults to
`vendor/bin`. To expose the commands as Composer scripts, add these entries to
the consuming project's existing `composer.json` scripts:

```json
{
  "scripts": {
    "efqs:docs:agents": "efqs docs:agents",
    "efqs:docs:context": "efqs docs:context",
    "efqs:docs:graphify": "efqs docs:graphify"
  }
}
```

The copied context and guide are starting points, not consuming-project facts.
Adapt project-specific setup and commands before relying on them.

## PHP conventions (strict)

- **`MethodScopeConventionRule`**: Enforces strict `self::`, `parent::`, and `$this->` scoping conventions on method calls.
- **`DataObjectPurityRule`**: Enforces strict immutable, property-only rules for class instances.
- **`RecordPurityRule`**: Enforces strict immutable, property-only Data Object rules applied to database row representation.


## PHP conventions (default with options)

- **`ClassMustBeFinalRule`:** Enforces classes are declared `final` by default. Use the `AllowInheritance` to explicitly allow class extension.
- Data Objects are PHP classes
