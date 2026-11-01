<!--
Minimal agent-context template from 8fold/php-architecture.

Copy this file to docs/agent-context.md in the consuming repository, replace
every placeholder, and delete these comments. Record only facts that are true
of that repository. When a section does not apply, write "None." rather than
deleting it, so readers can tell it was considered.
-->

# [Project name] agent context

Read this with [AGENTS.md](../AGENTS.md). This document records this
repository's actual commands, paths, environment, startup gates, exceptions,
and local adaptations of the shared rules. Both documents are binding.

## Source and scope

<!-- What this repository is, what it is not, and where its rules came from. -->

- [One or two sentences describing the project.]
- Shared rules: `8fold/php-architecture` at [commit or version].
- [What this repository does not have, such as a database, web server, or
  secret environment, so agents do not assume it.]

## Layout and runtime

<!-- Directories, namespaces, and runtime requirements an agent needs. -->

- PHP [version], with extensions: [list or "none beyond the defaults"].
- `src/`: [namespace and purpose].
- `tests/`: [namespace and purpose].

## Build, test, and lint commands

<!--
Required by Navigation, tools, and validation rule 3. List the exact entry
points from composer.json. Also list commands that look available but do not
work, so agents do not call them.
-->

- `composer install`: install dependencies from the lock file.
- `composer [script]`: [what it runs].

Not available: [commands that do not exist or do not work, or "None."].

## Enforcement boundaries

<!--
Supports Navigation, tools, and validation rule 7. Name the tools and rules
that actually enforce conventions here, and the guidance that is not enforced.
-->

- Enforced: [tool or rule, and what it checks].
- Not enforced: [guidance that no tool checks].

## Environment and secrets

<!--
Supports Security rules 2 and 4. Name each committed, secret-free template
agents may read. Name the exact source and destination for any secret copy
agents are directed to make, or write "None."
-->

- Secret-free templates: [path, or "None."].
- Directed secret copies: [exact source and destination, or "None."].
- Local preview: [URL and how to verify it serves this checkout, or "None."].

## Session startup

<!--
Required by Session and workspace rule 3. List the ordered gates for this
repository. Keep the knowledge graph gate (Navigation, tools, and validation
rule 1).
-->

1. Read `AGENTS.md` and this document. Check the branch and `git status`.
2. Knowledge graph: [how to query or update it, or its current status].
3. [Further gates, such as `composer install`.]

## Exceptions

<!--
Exceptions the shared rules allow, such as a required symlink (Human gates and
Git workflow rule 7). Link each one to the rule it uses.
-->

None.

## Local adaptations

<!--
Required by Information placement rules 2 and 3. Link each adaptation to the
rule it adapts. Offer useful adaptations back to 8fold/php-architecture.
-->

None.
