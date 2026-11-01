# 8fold Architecture for PHP

## Install the shared agent rules

Add `8fold/php-architecture` to the consuming project's Composer dependencies
and install them. From the consuming project's root, run:

```sh
./vendor/bin/efqs docs:agents
```

Composer makes `efqs` available in its configured `bin-dir`, which defaults to
`vendor/bin`. To also expose the command as `composer efqs:docs:agents`, add
this entry to the consuming project's existing `composer.json` scripts:

```json
{
  "scripts": {
    "efqs:docs:agents": "efqs docs:agents"
  }
}
```

The command copies this package's `AGENTS.md` to the project root. If the
destination is already identical, it reports that it is current; if it differs,
the command fails without overwriting it.

This is only the first setup step. The copied rules also require a
project-specific `docs/agent-context.md`, which this command does not create.
Adapt that document to the consuming project before relying on the rules.

## PHP conventions (strict)

- **`MethodScopeConventionRule`**: Enforces strict `self::`, `parent::`, and `$this->` scoping conventions on method calls.
- **`DataObjectPurityRule`**: Enforces strict immutable, property-only rules for class instances.
- **`RecordPurityRule`**: Enforces strict immutable, property-only Data Object rules applied to database row representation.


## PHP conventions (default with options)

- **`ClassMustBeFinalRule`:** Enforces classes are declared `final` by default. Use the `AllowInheritance` to explicitly allow class extension.
- Data Objects are PHP classes

