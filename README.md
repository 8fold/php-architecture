# 8fold Architecture for PHP

## PHP conventions (strict)

- **`MethodScopeConventionRule`**: Enforces strict `self::`, `parent::`, and `$this->` scoping conventions on method calls.
- **`DataObjectPurityRule`**: Enforces strict immutable, property-only rules for class instances.
- **`RecordPurityRule`**: Enforces strict immutable, property-only Data Object rules applied to database row representation.


## PHP conventions (default with options)

- **`ClassMustBeFinalRule`:** Enforces classes are declared `final` by default. Use the `AllowInheritance` to explicitly allow class extension.
- Data Objects are PHP classes

