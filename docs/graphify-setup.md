# Graphify setup

This is the shared setup baseline for 8fold PHP projects with no knowledge
graph: `graphify-out/graph.json` is absent at the project root.
Once a graph exists, follow the [Graphify workflow](graphify-workflow.md).

`efqs docs:graphify-setup` copies this guide into a consuming project's
`docs/` directory. It does not install Graphify, configure hooks, or create a
graph.

## Approval

Creating a knowledge graph requires explicit approval. Installing or upgrading
shared tools, changing user-level configuration, and enabling hooks are
separate setup actions, each requiring its own approval; approval to create a
graph does not imply them.

## Steps

1. **Agent:** report the missing graph and ask for approval to create it.
   Do not create it without approval.
2. **User:** approve or decline creation. If declined, the agent continues
   without a graph and uses direct source search.
3. **Agent:** check the [local tooling](#local-tooling). Report a missing CLI
   or skill, or a version mismatch, rather than installing or upgrading it.
4. **User:** install or upgrade the tooling if needed, or approve the agent
   doing so.
5. **Agent:** run the [first build](#first-build).
6. **User:** adopt an [artifact policy](#artifact-tracking) and record it in
   the project's agent context.

## Local tooling

Both the CLI and the skill are required. Every graph build and refresh needs
the skill; the CLI alone cannot extract prose without an API key.

| Prerequisite | Check |
|---|---|
| Graphify CLI 0.9.80 or newer | `graphify --version` |
| CLI executable directory on `PATH` (`~/.local/bin` for `uv`) | `command -v graphify` |
| Graphify skill for Copilot | `~/.copilot/skills/graphify/SKILL.md` exists |

If either is missing, ask whether to install it.

For an approved user-local installation, one option is:

```sh
uv tool install 'graphifyy>=0.9.80'
graphify copilot install
```

The second command installs the Copilot skill under `~/.copilot/skills/`.
It also refreshes that skill after a separately approved CLI upgrade. Ensure
the tool's executable directory is on `PATH`. Report CLI/skill version
mismatches; do not silently upgrade shared installations.

## First build

- After approval, follow the Graphify skill's `/graphify .` flow from the
  project root. `/graphify .` is an agent skill invocation, not a shell
  command.
- Report parser warnings and incomplete extraction, including the
  [PHP coverage gaps](graphify-workflow.md#php-coverage-and-usage-evidence).
  Verify the resulting graph before treating it as current.

## Artifact tracking

The shared tracking baseline commits the core artifacts so a checkout can
provide an immediately queryable graph:

- `graphify-out/graph.json`
- `graphify-out/GRAPH_REPORT.md`
- `graphify-out/graph.html`

Keep local metrics, caches, runtime metadata, and dated snapshots out of Git:

```gitignore
graphify-out/cost.json
graphify-out/cache/
graphify-out/manifest.json
graphify-out/.graphify_python
graphify-out/.graphify_root
graphify-out/[0-9][0-9][0-9][0-9]-*/
```

`graphify-out/.graphify_root` can contain a checkout-specific path; ignoring it
leaves it available to Graphify locally.

Copying this guide does not change `.gitignore` or adopt the tracking policy
automatically. Record the project's adopted artifact policy in its agent
context.

Leave Graphify backups enabled unless the project explicitly adopts Git
history as its recovery path and the core artifacts are already committed.
`GRAPHIFY_NO_BACKUP=1` is a project-specific opt-in, not a universal default.

## Optional automation

Composer aliases, Git hooks, and token-accounting helpers are project-specific.
Do not assume they exist merely because another project has them. Implement
and document them before enabling automation; copying this guide installs none.

## Reference

Adapted from the
[apps setup guide](https://github.com/8fold/site-mastering-the-mundane.apps/blob/6b5e42fd20e46e0ab9edf428291d7b7674b02b1d/docs/graphify-workflow-setup.md),
without its application-specific Composer commands, hooks, or helper scripts.
