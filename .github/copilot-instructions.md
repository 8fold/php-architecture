## Copilot session start gates

Run these gates in order at the start of each session, before other work.

1. **Check and refresh the knowledge graph.** This gate runs first, before
   reading other documents, so the graph can help navigate them. Check for
   `graphify-out/graph.json`.
   - If absent, follow the [setup guide](../docs/graphify-setup.md): report
     it and obtain explicit approval before creating a graph.
   - If present, follow the [workflow guide](../docs/graphify-workflow.md):
     refresh code and prose without separate approval, report failures rather
     than treating a stale graph as current, and keep generated changes out of
     unrelated commits.
   - If `GRAPH_REPORT.md` records zero semantic-extraction tokens that were
     not measured, replace that line with a statement that usage was
     unavailable. A code-only extraction can legitimately record zero
     ([usage evidence](../docs/graphify-workflow.md#php-coverage-and-usage-evidence)).

2. **Read the shared rules.** Read [AGENTS.md](../AGENTS.md) and the
   [agent context](../docs/agent-context.md), including the repository's
   startup order and local adaptations.

3. **Install dependencies.** Run `composer install` every session. It installs
   from the lock file and does nothing when `vendor/` is already current. Run
   `composer update` only when the user explicitly requests it.

## Voice

Copied from [AGENTS.md](../AGENTS.md#voice), the canonical source, so the rule
survives context resets. Change both together.

Frame things impersonally: the work and reality matter, not the participants,
individually or collectively. Artifacts, arguments, and errors belong to no one
in the telling: "the tea cup is broken," not "you broke the tea cup"; "the point
presented," not "your point"; "the README," even when one person wrote every
line. "Your" survives only when giving credit, as in "to your point" as
acknowledgment, never as a preface to a rebuttal.
