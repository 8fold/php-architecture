# Glossary

Use these terms consistently in code, documentation, and agent instructions.

## Functions and methods

- **Function**: a named `function` declared outside a class-like structure, in
  the global namespace or a named namespace.
- **Method**: a `function` declared in a class-like structure (`class`,
  `trait`, `enum`, or `interface`). Interface and abstract methods are declared
  without a body.
- **Instance Method**: a method not declared `static`; called on an instance
  (`$this->method()`, `$object->method()`).
- **Static Method**: a method declared `static`; called without an instance
  (`self::method()`, `ClassName::method()`). PHP also allows calls through an
  instance.

## Properties

- **Instance Property**: a property not declared `static`; each instance holds
  its own value (`$this->property`).
- **Static Property**: a property declared `static`; shared by the class-like
  structure (`self::$property`, `static::$property`).
