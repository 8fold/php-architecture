---
applyTo: "src/*.php,tests/*.php"
---

Terms follow [docs/glossary.md](../../docs/glossary.md).

- Use `self::` for Instance and Static Methods in the same class/trait by default.
- Use `static::` for Instance and Static Methods intended to be overridden when
  the class is designed for extension.
- Use `parent::` to call inherited implementations of Instance and Static Methods.
- Calling Instance Methods with `self::`, `static::`, or `parent::` requires an
  instance context (`$this`).
- Use `$this->` strictly for instance property access.
- Use `self::`, `parent::`, or `static::` for static property access.
