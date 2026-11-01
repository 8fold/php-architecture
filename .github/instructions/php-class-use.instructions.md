---
applyTo: "src/*.php,tests/*.php"
---

- Use `self::` for methods in the same class/trait by default.
- Use `static::` when the class is designed for extension and the called method is intended to be overridden.
- Use `parent::` to call inherited implementations.
- Use `$this->` strictly for instance property access.
- Use `self::`, `parent::`, or `static::` for static property access.
