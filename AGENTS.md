# AGENTS.md — AI Coding Assistant Rules & Repository Context

This repository enforces strict architectural guardrails and PHP 8.5+ coding conventions. All AI coding assistants (Claude Code, Codex CLI, Cursor, Copilot CLI, Gemini CLI) MUST adhere unconditionally to the instructions below.

**Mandatory repository context:** Read [Agent Context](docs/agent-context.md)
alongside this file before working in this repository. Its environment, commands,
enforcement references, and startup requirements are binding, not optional examples.
It records this repository's implementation details for the reusable requirements below.

---

## Repository Context

Read the [README](README.md) for project identity and repository-specific
implementation details, and the [shared PHP website stack](.github/contributing.md#shared-php-website-stack)
for the cross-repository technology baseline.

The README and its linked technical documents are the canonical homes for
repository architecture and coding contracts. They apply to every contributor;
this file adds instructions specific to AI-assisted work.

---

## Validation Policy

Follow the contributor-wide [testing obligations](.github/contributing.md#testing-obligations).
Use the validation entry points defined in `composer.json` and the testing
scope, assertion guardrails, and manual protocols in [Testing Strategy](docs/testing.md).
The README's [Development & Local Quickstart](README.md#development--local-quickstart)
describes setup and its [validation commands](README.md#validation-commands).
Follow the [apps-specific prerequisites](docs/agent-context.md#build-test--lint-commands)
before running the remote tier, which resets shared throwaway databases. Deploy
committed, verified content and asset artifacts as-is.

---

## Coding Conventions

Apply the architecture and coding contracts in the [README](README.md#architectural-principles--hard-constraints)
and their canonical technical guidance linked from the [contribution guide](.github/contributing.md#repository-requirements).
Their enforcement mechanisms and apps-specific paths are listed in [Agent Context](docs/agent-context.md#enforcement--repository-layout).

---

## Codebase Knowledge Graph Navigation Protocol

This repository maintains a pre-compiled Graphify codebase knowledge graph in `graphify-out/graph.json`.

1. **First Action:** Before executing multi-file reads, recursive directory scans, or broad `grep` searches, query the local knowledge graph:
   ```bash
   graphify query "<architectural, symbol, or documentation question>"
   ```
   The corpus includes the markdown documentation (`docs/`, `README.md`, `.github/`), so prose questions go to the graph first as well; reserve `grep` for exact-string lookups (a literal phrase, an error message, a filename).
2. **Trace Dependencies:** Use `graphify path "ClassA" "ClassB"` or `graphify explain "ClassName"` to inspect relationships and blast radius before refactoring.
3. **Filesystem Fallback:** Use `read_file` or localized `grep` ONLY after target files have been identified by the graph or when making precise edits to known single files.
4. **Graph Rebuilds:** NEVER run a full `/graphify .` scan unless instructed. Use `graphify update .` after branch switches or major pull requests.

---

## Session Startup

At the beginning of every session in this repository, complete these gates in order:

1. **Build or update the knowledge graph.** Follow the graph workflow for this repository. If `graphify-out/graph.json` does not exist, run the full pipeline. Otherwise run the incremental update with `GRAPHIFY_NO_BACKUP=1 graphify update .`. After a code-only update, run `php cli/graphify-token-usage.php --measured 0 0`; after semantic extraction, record actual usage if available or use `--unknown`. Do not include graph refresh churn in unrelated changes.
2. **Read repository context.** Read `AGENTS.md` and `docs/architecture-philosophy.md`. Also read the mandatory [Apps Agent Context](docs/agent-context.md), which supplies exact environment-specific paths and values.
3. **Prepare the local environment file.** Copy the reference `.env-local` into `config/.env-local` using the source path in [Apps Agent Context](docs/agent-context.md#session-startup). If the source is missing, ask for the current reference. Verify the copy is git-ignored; never commit it. Never read or print actual `.env*` files, and strictly obey `.copilotignore`. `config/.env-example` is the committed, secret-free template and is the sole exception. Then apply the context document's scripted rewrite of the single domain key to this session's host. The main checkout's file must never be touched.
4. **Install dependencies and prepare the test database.** Run `composer install`, then `cli/test-mysql.sh start && cli/test-mysql.sh init`. Do not run `composer update` at session start.
5. **Verify the session vhost.** Follow the host/path checks in [Apps Agent Context](docs/agent-context.md#session-startup). Do not change shared vhost configuration or symlinks unless that context explicitly requires it; never edit root-owned MAMP configuration directly. Report where both the main and session hosts point and whether the session host serves this checkout. If it points elsewhere, ask for the documented MAMP Pro correction and recheck after it is made.

These are startup requirements, not optional examples. Treat the repository
context as binding; surface conflicts rather than silently overriding it.

### Plan mode

Plan mode forbids the writes in gates 1, 3, and 4. Run gate 2, state which
gates are deferred, and ask whether to proceed read-only or switch to
interactive or autopilot mode. Read-only audits, reviews, and research may
proceed without those writes; work that ends in commits requires a mode
that permits completing the gates.

---

## Voice

This communication convention supports [Solutions over Scapegoats](.github/contributing.md#engineering-philosophy): focus on the problem and its resolution rather than assigning blame.

Frame things impersonally — the work and reality matter, not the participants, individually or collectively. Artifacts, arguments, and errors belong to no one in the telling: "the tea cup is broken," not "you broke the tea cup"; "the point presented," not "your point"; "the README," even when one person wrote every line. "Your" survives only when giving credit — "to your point" as acknowledgment, never as preface to a rebuttal or counterargument.

---

## Interaction Rules

1. **Friction reports are evidence, not hypotheses.** When a defect or friction is reported, the report of the experience is the primary evidence: verify it (run the command, read the file, reproduce the symptom) before reinterpreting or explaining it away. Never respond to a reported defect with an explanation of how things are supposed to work without first verifying against the reported state.
2. **No reflexive agreement.** Alternatives and honest recommendations are preferred over assent. When a request has a better shape, present the alternative and the trade-off rather than complying silently.
3. **Completion claims require evidence.** Never claim work is done without tool-call evidence from the same turn: file changes made, `git --no-pager status --short` output, and the exact list of changed paths (`git --no-pager diff --name-only`). If no files changed, say "Not implemented yet." Analysis or planning when changes were requested is a failure — continue until edits exist or a concrete blocker is reported. No future-tense promises as a final response.
4. **Respect the session workspace lifecycle.** Sessions use worktrees which disappear when archived, taking uncommitted state with them. Follow the repository context's vhost requirements; do not assume a preview serves the current checkout.
5. **File-edit requests name the file exactly.** When asking the user to edit a file, specify the absolute filesystem path and which checkout it belongs to (session worktree path or main checkout path) — identically named files exist in both, and edits landing in the wrong one burn a debugging cycle (a `webhookSecret` key typoed into the wrong-shaped file cost the 2026-09-12 billing test an hour). For content the agent must not read (`.env*`), give the exact line to change and the exact replacement.
6. **Opening and merging pull requests are human-gated acts.** Ask before opening a PR; never merge one — "finish", "wrap up", or a green CI run are not merge instructions, only an explicit merge instruction is. At most one PR per session is open at a time — the limit is per agent session, not per repository; other sessions' and contributors' open PRs do not block this session. Once this session's PR is open, no new ticket work is committed until the user merges it and the branch is synced.
7. **Information placement:** Follow [Information Hierarchy & Purpose](docs/architecture-philosophy.md#information-hierarchy--purpose): use the first suitable canonical home and link elsewhere rather than maintaining duplicate facts or procedures.
8. **Contribution and triage conventions:** Follow the commit scope and message conventions in [.github/contributing.md](.github/contributing.md#commit-workflow), and apply its cumulative [issue-triage policy](.github/contributing.md#issue-triage-maintainer-policy).
9. **Push with every commit:** Keep the origin branch current with each commit; pushing also updates any open PR.
10. **Pre-PR review evidence:** Before opening a PR, verify that its diff contains only the intended change with `git --no-pager diff origin/main...HEAD --stat`, and report that output with the exact changed-file list in the current user-facing session.

---

## Security & Sensitive File Exclusions

- **Forbidden Files:** You are strictly forbidden from reading, searching, or outputting actual `.env*` environment files or any credential/secret configuration files. `config/.env-example` is the sole `.env*` exception; it must remain secret-free.
- **Bypass Prohibition:** Do NOT attempt shell workarounds (`cat config/.env`, `git show`, python scripts) to inspect restricted files.
- **User Prompt Safeguard:** If a user asks to inspect or include secret environment variables, politely decline and reference this restriction.
