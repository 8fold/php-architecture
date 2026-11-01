# php-architecture agent context

Read this with [AGENTS.md](../AGENTS.md). This document records this
repository's actual commands, paths, environment, startup gates, exceptions,
and local adaptations of the shared rules.

<!-- Project identity and boundaries. -->
## Source and scope

- `8fold/php-architecture` is the canonical home of the shared agent–user rules
  in `AGENTS.md`. It is also a complete PHP project, with code, tests, and tool
  configuration. Its own repository files (for example `.copilotignore` and
  this document) are the baselines that consuming 8fold projects start from.
- The shared rules were consolidated from
  `8fold/site-mastering-the-mundane.apps` (`AGENTS.md` at 15782c3, a private
  repository) and
  [`8fold/site-masteringthemundane.com`](https://github.com/8fold/site-masteringthemundane.com/blob/bc4f99d3aadab909385d30e1ad7022444bc403a4/AGENTS.md)
  (`AGENTS.md` at bc4f99d).
- This is a library: there is no web server, preview, database, or secret
  environment setup.

<!-- Directory and runtime map. -->
## Layout and runtime

- PHP `^8.5`, as declared in `composer.json`.
- `src/Attributes/`: PHP attributes (`Eightfold\Attributes\`).
- `src/PHPStan/`: PHPStan rules (`Eightfold\PHPStan\`), registered in
  `extension.neon`.
- `src/PHPCodeSniffer/`: PHPCS sniffs (`Eightfold\PHPCodeSniffer\`), not yet
  registered in `phpcs.xml`.
- `tests/`: PHPUnit tests (`Eightfold\Tests\`), with fixtures under
  `tests/PHPStan/Fixtures/` and `tests/PHPCS/Fixtures/`.
- `bin/`: Composer-exposed command-line tools for consuming projects.
- `docs/`: project documentation, including the agent context copied by
  consuming projects.
- `.github/instructions/`: Copilot instructions for PHP class and test
  conventions.
- Tool configuration: `phpcs.xml`, `phpstan.neon`, `phpunit.xml`.

<!-- Commands and their known limitations. -->
## Build, test, and lint commands

The entry points are defined in `composer.json`:

- `composer install`: install dependencies from the lock file.
- `composer check`: run `check:style`, `check:analyze`, and `check:test`.
- `composer check:style`: PHPCS with `phpcs.xml`.
- `composer check:analyze`: PHPStan with `phpstan.neon`.
- `composer check:test` (alias `composer test`): PHPUnit.
- `composer production` (alias `composer prod`): `check`, then
  `optimize-autoloader`.

The current local environment uses MAMP PHP 8.5.2
(`/Applications/MAMP/bin/php/php8.5.2/bin/php`). Do not assume that path
exists elsewhere.

Not functional yet; do not rely on these:

- `composer local` only runs `check`. Its description (git hooks, build) is
  aspirational.
- `composer graph:refresh` calls `cli/graphify-token-usage.php`, which does not
  exist.

<!-- Tool enforcement versus written guidance. -->
## Enforcement boundaries

- Enforced: PHPCS style checks, PHPStan analysis, and PHPUnit tests through the
  corresponding `composer check:*` scripts.
- Not enforced: guidance in `AGENTS.md` unless a tool or check explicitly
  implements it.

<!-- Secret templates, copies, and previews. -->
## Environment and secrets

- Secret-free templates: None.
- Directed secret copies: None.
- Local preview: None; this is a library with no web server.

<!-- Known repository limitations. -->
## Current state

The repository is mid-consolidation. `composer check` is not expected to pass:
several PHP files under `src/Attributes/` do not parse, and `extension.neon`
registers a rule that does not exist. Report failures under
[Navigation, tools, and validation](../AGENTS.md#navigation-tools-and-validation)
rule 5. Do not fix unrelated code as part of other work.

<!-- Ordered repository-specific startup gates. -->
## Session startup

1. Read `AGENTS.md` and this document. Check the branch and `git status`.
2. Knowledge graph: none is maintained yet (see adaptations). Tell the user
   that it is missing. Do not build one without asking.
3. If `vendor/` is missing and the task needs validation, run
   `composer install`.

<!-- Exceptions permitted by the shared rules. -->
## Exceptions

None.

<!-- Local deviations from the shared rules. -->
## Local adaptations and pending deliverables

Each entry links to the shared rule it adapts.

- [Navigation, tools, and validation](../AGENTS.md#navigation-tools-and-validation)
  rule 1: no knowledge graph or setup guide exists yet. Until the guide
  exists, ask before building a graph. Graphify writes `graphify-out/cache/`
  into the repository; keep it out of commits.
- [Human gates and Git workflow](../AGENTS.md#human-gates-and-git-workflow)
  rule 1: the shared contribution guide (full change-classification
  definitions) does not exist yet.
- [Human gates and Git workflow](../AGENTS.md#human-gates-and-git-workflow)
  rule 4: there is no pull request template yet. Describe each change and
  classify it as refactoring or re-engineering in the pull request body.
- [Information placement](../AGENTS.md#information-placement) rule 1: the
  information hierarchy links to `8fold/site-masteringthemundane.com` at
  bc4f99d until this package ships its own copy.
