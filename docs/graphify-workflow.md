# Graphify workflow

This is the shared workflow baseline for 8fold PHP projects with an existing
knowledge graph: `graphify-out/graph.json` is present at the project root. If
it is absent, follow the [Graphify setup](graphify-setup.md) instead.

`efqs docs:graphify-workflow` copies this guide into a consuming project's
`docs/` directory. It does not install Graphify, configure hooks, or create a
graph.

## Refresh

- Refresh after every successful `git pull` an agent runs, before relying on
  the graph again. Report the refresh result with the pull result; a failed
  refresh does not make the pull fail.
- Updating an existing graph requires no separate approval. Installing or
  upgrading tools and enabling hooks still require the approvals in the
  [setup guide](graphify-setup.md#approval).
- Use the Graphify skill's `/graphify --update` flow to refresh both code and
  prose. `/graphify --update` is an agent skill invocation, not a shell
  command. The skill needs no API key; the running agent extracts prose.
- Do not run `graphify . --update` or `graphify extract` from a shell for this
  refresh. Those are headless CLI commands that need an external LLM key for
  prose.
- `graphify update .` is a code-only CLI refresh. It is not a substitute for
  the skill's semantic refresh after documentation changes. A code-only
  post-merge hook has the same limit.
- If the skill is not installed, ask whether to install it
  ([local tooling](graphify-setup.md#local-tooling)).
- If a refresh fails or is skipped, preserve the existing graph and report
  that it may be stale rather than claiming the refresh succeeded.
- Keep generated changes out of unrelated commits.

### Success

A refresh succeeded only when all of these hold:

- `graphify-out/graph.json` and `graphify-out/GRAPH_REPORT.md` were
  regenerated in this run.
- `graphify-out/manifest.json` was saved, so the next refresh compares
  against this one.
- Every changed document was extracted, or each one that failed is listed
  as unextracted.
- Parser warnings and [usage](#php-coverage-and-usage-evidence) were reported.

Report anything short of this as a partial refresh and the graph as possibly
stale.

### Symptoms

| Message | Cause | Action |
|---|---|---|
| `no LLM API key found` | A headless CLI command was run instead of the skill. | Use the skill's `/graphify --update` flow. No key is needed. |
| `manifest.json missing` | The manifest is untracked, so a fresh checkout or worktree has none. | Expected. The skill saves one at the end of a successful refresh. |
| `graphify: command not found` | The CLI is missing or not on `PATH`. | Ask whether to install it ([local tooling](graphify-setup.md#local-tooling)). |

## Navigate

Follow the [navigation rules](../AGENTS.md#navigation-tools-and-validation):
query the graph first for codebase relationships and verify actual source
before editing. Exact-string lookups may use filesystem search directly.

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
