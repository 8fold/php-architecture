## Copilot session start gates

Run these gates in order at the start of each session, before other work.

1. **Check and refresh the knowledge graph.** This gate runs first, before
   reading other documents, so the graph can help navigate them. Check for
   `graphify-out/graph.json`.
   - If absent, follow the [setup guide](../docs/graphify-setup.md): report
     it and obtain explicit approval before creating a graph.
   - If present, follow the [workflow guide](../docs/graphify-workflow.md):
     refresh code and prose without separate approval, report failures rather
     than treating a stale graph as current, use the graph for navigation as
     required by [AGENTS.md](../AGENTS.md#navigation-tools-and-validation),
     and keep generated changes out of unrelated commits.

## Voice

Copied from [AGENTS.md](../AGENTS.md#voice), the canonical source, so the rule
survives context resets. Change both together.

Frame things impersonally: the work and reality matter, not the participants,
individually or collectively. Artifacts, arguments, and errors belong to no one
in the telling: "the tea cup is broken," not "you broke the tea cup"; "the point
presented," not "your point"; "the README," even when one person wrote every
line. "Your" survives only when giving credit, as in "to your point" as
acknowledgment, never as a preface to a rebuttal.
