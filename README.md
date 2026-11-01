# 8fold Architecture for PHP

## Install the shared agent rules and context

Add `8fold/php-architecture` to the consuming project's Composer dependencies
and install them. From the consuming project's root, copy the shared rules and
the project context:

```sh
./vendor/bin/efqs docs:agents
./vendor/bin/efqs docs:context
```

The first command copies `AGENTS.md` to the project root. The second copies
`docs/agent-context.md`, creating `docs/` if needed. Either command leaves an
identical destination unchanged and refuses to overwrite a different file.

Composer makes `efqs` available in its configured `bin-dir`, which defaults to
`vendor/bin`. To expose the commands as Composer scripts, add these entries to
the consuming project's existing `composer.json` scripts:

```json
{
  "scripts": {
    "efqs:docs:agents": "efqs docs:agents",
    "efqs:docs:context": "efqs docs:context"
  }
}
```

The copied context is a starting point, not consuming-project facts. Adapt it
to the consuming project before relying on the rules.

## PHP conventions (strict)

- **`MethodScopeConventionRule`**: Enforces strict `self::`, `parent::`, and `$this->` scoping conventions on method calls.
- **`DataObjectPurityRule`**: Enforces strict immutable, property-only rules for class instances.
- **`RecordPurityRule`**: Enforces strict immutable, property-only Data Object rules applied to database row representation.


## PHP conventions (default with options)

- **`ClassMustBeFinalRule`:** Enforces classes are declared `final` by default. Use the `AllowInheritance` to explicitly allow class extension.
- Data Objects are PHP classes
