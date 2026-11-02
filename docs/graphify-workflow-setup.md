# Graphify workflow and setup

This is the shared setup baseline for 8fold PHP projects. Read
[AGENTS.md](../AGENTS.md) and the [agent context](agent-context.md) first;
the context records project-specific commands, startup order, and adaptations.

`efqs docs:graphify` copies this guide into a consuming project's `docs/`
directory. It does not install Graphify, configure hooks, or create a graph.

## Creation and update permissions

Creating a knowledge graph requires explicit approval. Updating an existing
graph requires no separate approval. Installing or upgrading shared tools,
changing user-level configuration, and enabling hooks are separate setup
actions, not implied by permission to refresh a graph.

Check for `graphify-out/graph.json` at the project root. If absent, report
the missing graph and ask before creating it. If an update fails, preserve
the existing graph and report that it may be stale rather than claiming
the refresh succeeded.

## Local tooling

Use Graphify 0.9.80 or newer for this PHP baseline and a matching Graphify
skill. Check the installed CLI with:

```sh
graphify --version
```

For an approved user-local installation, one option is:

```sh
uv tool install 'graphifyy>=0.9.80'
graphify copilot install
```

The second command installs the Copilot skill under `~/.copilot/skills/`.
It also refreshes that skill after a separately approved CLI upgrade. Ensure
the tool's executable directory is on `PATH`. Report CLI/skill version
mismatches; do not silently upgrade shared installations.

## Build, refresh, and navigate

- After approval for the first build, follow the Graphify skill's
  `/graphify .` flow from the project root.
- For an existing graph, use the skill's `/graphify --update` flow to refresh
  both code and prose. No separate update approval is needed.
- `graphify update .` is a code-only CLI refresh. It is not a substitute for
  the skill's semantic refresh after documentation changes.
- Report parser warnings and incomplete extraction. Verify the resulting
  graph before treating it as current.
- Follow the [navigation rules](../AGENTS.md#navigation-tools-and-validation):
  query the graph first for codebase relationships and verify actual source
  before editing. Exact-string lookups may use filesystem search directly.

The `/graphify` forms above are agent skill invocations, not shell commands.

## Artifacts and recovery

The shared tracking baseline commits the core artifacts so a checkout can
provide an immediately queryable graph:

- `graphify-out/graph.json`
- `graphify-out/GRAPH_REPORT.md`
- `graphify-out/graph.html`

Keep local metrics, caches, and dated snapshots out of Git:

```gitignore
graphify-out/cost.json
graphify-out/cache/
graphify-out/manifest.json
graphify-out/.graphify_python
graphify-out/[0-9][0-9][0-9][0-9]-*/
```

Copying this guide does not change `.gitignore` or adopt the tracking policy
automatically. Record the project's adopted artifact policy in its agent
context. Keep generated changes out of unrelated commits.

Leave Graphify backups enabled unless the project explicitly adopts Git
history as its recovery path and the core artifacts are already committed.
`GRAPHIFY_NO_BACKUP=1` is a project-specific opt-in, not a universal default.

## Optional automation

Composer aliases, Git hooks, and token-accounting helpers are project-specific.
Do not assume they exist merely because another project has them. Implement
and document them before enabling automation; copying this guide installs none.

A code-only post-merge refresh does not refresh documentation nodes. Report
skipped or failed refreshes so automation does not imply that a stale graph
is current.

## PHP coverage and usage evidence

Graphify's PHP parser may not cover all valid PHP 8.5 syntax, including some
void casts and enum-qualified `match` arms. Report coverage gaps rather than
rewriting valid application code or suppressing warnings to make a graph
appear complete.

Record measured semantic-extraction usage when the host exposes it. Otherwise,
report usage as unavailable, not measured zero. A code-only extraction can
legitimately have no semantic usage. Do not interpret generated placeholder
zeros as measurements. This guide does not supply an accounting helper.

## Reference

Adapted from the
[apps setup guide](https://github.com/8fold/site-mastering-the-mundane.apps/blob/6b5e42fd20e46e0ab9edf428291d7b7674b02b1d/docs/graphify-workflow-setup.md),
without its application-specific Composer commands, hooks, or helper scripts.
