## Copilot session start gates

Run these gates in order at the start of each session, before other work.

1. **Check and refresh the knowledge graph.** First read
   [AGENTS.md](../AGENTS.md) and the [agent context](../docs/agent-context.md),
   following the repository's [startup order](../docs/agent-context.md#session-startup)
   and [local graph policy](../docs/agent-context.md#local-adaptations-and-pending-deliverables).
   Check for `graphify-out/graph.json`. If absent, report it and obtain explicit
   approval before creating a graph. Follow the
   [setup guide](../docs/graphify-workflow-setup.md) and Graphify skill for
   creation or refresh. Updates to an existing graph require no separate
   approval. Refresh both code and prose. Report update
   failures rather than silently treating a stale graph as current. Use it for
   navigation as required by [AGENTS.md](../AGENTS.md#navigation-tools-and-validation), and
   keep generated changes out of unrelated commits.
