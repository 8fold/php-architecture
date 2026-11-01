# AGENTS.md — Shared agent–user rules

These rules govern how AI coding assistants (Claude Code, Codex CLI, Cursor,
Copilot CLI, Gemini CLI, and others) work with people on 8fold PHP projects.
`8fold/php-architecture` is their canonical home.

Read this file and the repository's [agent context](docs/agent-context.md)
before working. The agent context records this repository's actual commands,
paths, environment, startup gates, exceptions, and local adaptations of these
rules. Both documents are binding.

---

## Communication

1. **Recommend, don't just agree.** Prefer alternatives and honest
   recommendations to reflexive agreement. When a request has a better shape,
   present the alternative and its trade-off instead of complying silently.
2. **Reported friction is evidence.** Treat a reported defect or friction as the
   primary evidence. Verify the reported state (run the command, read the
   file, reproduce the symptom) before reinterpreting it or explaining how things
   are supposed to work.
3. **Surface conflicts.** When instructions, repository context, and observed
   state disagree, report the conflict instead of silently picking one.
4. **Name the evidence.** Distinguish what was verified from what was inferred,
   and local checks from deployed behavior. Name the actual method, for
   example an HTTP check rather than HTTPS, or source inspection rather than a
   browser test.
5. **Name files exactly when asking for edits.** When asking someone to edit a
   file, give its absolute path and which checkout it belongs to. For files the
   agent must not read, give the exact line to change and its replacement.

### Voice

Frame things impersonally: the work and reality matter, not the participants,
individually or collectively. Artifacts, arguments, and errors belong to no one
in the telling: "the tea cup is broken," not "you broke the tea cup"; "the point
presented," not "your point"; "the README," even when one person wrote every
line. "Your" survives only when giving credit, as in "to your point" as
acknowledgment, never as a preface to a rebuttal.

---

## Evidence and completion

These rules apply whenever a request asks for changes to files.

1. **Claim completion only with evidence.** Do not say "done", "implemented",
   "fixed", or anything equivalent unless tool calls in the same turn changed
   the files.
2. **Report the evidence.** Every completion message includes:
   - the output of `git --no-pager status --short`;
   - the output of `git --no-pager diff --name-only`;
   - the exact list of changed paths, including new, deleted, and renamed files.
     `git diff --name-only` omits untracked files, so build the list from
     `status`.
3. **Say when nothing changed.** If no files changed, say "Not implemented yet."
4. **Analysis is not delivery.** When changes were requested, analysis or a plan
   alone is a failure. Continue until edits exist, or report a concrete blocker.
5. **Do not end on a promise.** A final response must not be a future-tense
   promise such as "I'll do X now".
6. **Pre-PR evidence.** Before opening a pull request, run
   `git --no-pager diff origin/main...HEAD --stat` to confirm the diff contains
   only the intended change. Report that output and the exact list of changed
   files in the user-facing session.

---

## Human gates and Git workflow

1. **Small commits, one kind each.** Prefer small, reviewable commits. Each
   commit is exactly one of:
   - **Refactoring:** preserves both observable behavior and explicit
     contracts. It changes internal structure only.
   - **Re-engineering:** changes observable behavior or an explicit
     developer-facing contract, such as a public API, URL, or configuration.

   Never mix the two in one commit. A commit may touch several files to complete
   its change. Full definitions, including how tests may change in a
   refactoring, will live in the shared contribution guide (see the agent
   context for its status). Commit messages follow
   [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/).
2. **One open pull request per session, of one or more commits.** Each agent
   session has at most one open pull request at a time. It is built from as
   many small commits as its change needs, refactoring and re-engineering
   commits alike. Keep adding commits within that pull request's scope,
   including review fixes. Work outside that scope waits until the pull request
   is merged and the branch is synced. The limit is per session, not per
   repository; other sessions' and contributors' pull requests do not block
   this one.
3. **Opening a pull request is human-gated; updating one is not.** Ask the user
   before opening a new pull request. Commits and pushes to the session branch
   need no separate approval, including pushes that update an existing pull
   request.
4. **Merging is user-controlled.** Merge only when the user explicitly asks.
   "Finish", "wrap up", and a green CI run are not merge instructions.
5. **Follow the pull request template.** Inspect the repository's pull request
   template and complete it, classifying each meaningful change as refactoring
   or re-engineering. Keep each pull request scoped to its stated change.
6. **Commit and push the session branch.** Keep the remote branch current after
   each commit. Do not commit directly to `main` unless the user explicitly
   directs it.
7. **Leave shared environments alone.** Do not change anything outside the
   session's checkout without explicit user direction. That includes the main
   checkout, other sessions' checkouts, shared server or virtual host
   configuration, and shared symlinks. Exceptions:
   - The repository's agent context may name a specific action that is
     required.
   - Never edit root-owned or tool-generated configuration directly. Report the
     mismatch and request a user-managed correction.

---

## Session and workspace

1. **Read the rules and the context first.** Before working, read this
   document and the repository's `docs/agent-context.md`. The agent context
   records the repository's actual commands, paths, environment, and
   exceptions. Every consuming repository must have one, starting from the
   package's [agent context](docs/agent-context.md). Both documents are
   binding. Report conflicts between them
   ([Communication](#communication) rule 3).
2. **Inspect before changing.** Check the branch and `git status` at the start.
   Preserve unrelated work in progress.
3. **Complete the startup gates in order.** The agent context lists the
   repository's ordered startup gates, if it has any. Complete them before
   other work and report what each one found. Do not borrow gates, commands, or
   environment steps from another repository; they are not portable facts.
4. **Install, don't upgrade.** When dependencies are missing or the agent
   context requires it, install from the lock file (`composer install`).
   Never run `composer update` as routine startup.
5. **Respect the session mode.** If the current mode forbids writes that a
   startup gate needs, run the read-only gates and state which gates were
   deferred. Then ask whether to continue read-only or switch to a mode that
   permits writes. Read-only audits, reviews, and research can proceed. Work
   that ends in commits requires the gates.
6. **Treat worktrees as temporary.** Session worktrees, and any uncommitted
   work in them, are deleted when the session is archived. Commit and push
   approved work ([Human gates and Git workflow](#human-gates-and-git-workflow)
   rules 1 and 6). Do not assume a preview or local server serves the current
   checkout; verify it before relying on it.

---

## Navigation, tools, and validation

1. **Navigate with the knowledge graph first.** Every 8fold PHP project
   maintains a knowledge graph. Query it before broad reads, recursive scans,
   or wide searches. Exact-string lookups (a literal phrase, an error message,
   a filename) may use search directly. If the graph is missing or unusable,
   tell the user. Then build the infrastructure, if necessary, by following the
   knowledge graph setup guide (see the agent context for its status).
2. **Verify source before editing.** Graphs, indexes, and summaries can be
   stale. Read the actual files before changing them. If navigation results
   seem inconsistent, say so.
3. **Use the defined entry points.** Use the commands defined in `composer.json`
   and listed in the agent context. Do not invent setup, build, or test commands.
4. **Validate in proportion to the change.** Run the validation that covers the
   change. Changes to documentation alone do not need the code suite, unless
   they change documented commands or behavior that needs verifying.
5. **Never trade coverage for a green build.** When a test fails, classify the
   failure before changing assertions. Do not remove coverage or rewrite
   expectations just to pass. Test counts are not targets.
6. **Keep generated churn out of unrelated work.** Do not commit regenerated
   artifacts, such as graphs, caches, or compiled assets, as part of an
   unrelated change.
7. **Guidance is not enforcement.** A rule in documentation does not mean a tool
   enforces it. A new rule is not permission to refactor existing code across
   the repository. Apply new guidance to the code being changed.

---

## Security

1. **Never expose secrets.** Do not read, search, or output actual
   environment files (`.env` and `.env-*`), credentials, or secret configuration.
2. **Templates are the exception.** Committed, secret-free templates named in
   the agent context, such as `config/.env-example`, may be read and maintained.
   Never put real secrets in them.
3. **No workarounds.** Do not bypass these exclusions through Git history,
   scripts, other tools, or shell tricks.
4. **Copy secrets only as directed.** Copy a secret file only when the agent
   context names its exact source and destination. Copy it without reading or
   printing it, and confirm that the destination is ignored by Git. Never copy
   secrets from another checkout as an assumed prerequisite.
5. **Honor agent ignore files.** Every project has a `.copilotignore`, starting
   from this package's own `.copilotignore` as the baseline. Obey it and any
   equivalent ignore file for other agent tools.
6. **Decline requests to reveal secrets.** If asked to show or include secret
   values, decline politely and point to this section.

---

## Information placement

1. **One home per fact; cite when paraphrasing.** Each fact or procedure has one
   canonical home, chosen by
   [Information Hierarchy & Purpose](https://github.com/8fold/site-masteringthemundane.com/blob/bc4f99d3aadab909385d30e1ad7022444bc403a4/docs/architecture-philosophy.md#information-hierarchy--purpose).
   Other documents may paraphrase it briefly for their audience when they
   link to the canonical home, as the commit rule does with the
   change-classification definitions. A paraphrase must not change the
   meaning. If they disagree, the canonical home wins; report the drift.
   Propose a better placement instead of silently moving established
   information.
2. **Adapt locally; contribute back.** These rules are maintained in
   `8fold/php-architecture`. Any consuming project may adapt or experiment with
   them locally. Record the adaptation in its agent context (rule 3).
   First-party projects (8fold, `Eightfold` namespace) should offer a useful
   adaptation back by recommending the change, opening an issue, or submitting
   a pull request to `8fold/php-architecture`. Opening an issue or pull request
   in another repository is human-gated
   ([Human gates and Git workflow](#human-gates-and-git-workflow) rules 3
   and 7).
3. **The agent context records facts, exceptions, and adaptations.** A
   repository's `docs/agent-context.md` records that repository's actual
   commands, paths, environment, and startup gates. It may also name specific
   exceptions these rules allow, such as a required symlink or a directed
   secret copy, and local adaptations of these rules, each with a link to the
   rule it adapts. If it conflicts with these rules without saying so, report
   the conflict ([Communication](#communication) rule 3) instead of choosing
   one silently.
