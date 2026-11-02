# Callable type analysis and attribute inference prototype

These drafts are outside the Composer autoload mappings and are not supported
package APIs. They remain in the repository as design material; they may still
be present in a package archive and cannot run as-is.

The inferer refers to missing result and RFC 2119 types, subtype rule methods,
and inspector methods whose signatures do not match the inspector draft. The
inspector also has unresolved parser types. `CallableSubtypeInference.php`
preserves the former `CallableSubtype::fromFlags()` sketch, which refers to a
missing `CallableBaseType` and undeclared enum cases. A supported inference API
requires its contracts and behavior to be designed and tested first.

`CallableEvaluators.php` and the adjacent `Tests/` directory preserve the
callable-analysis experiment. Its tests and fixtures are outside the supported
PHPUnit suite. They depend on missing types and a rule that is not implemented;
the compliant callable fixture also contains a pre-existing parse error.
