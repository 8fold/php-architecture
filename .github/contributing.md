# Contributing

Issues, documentation, and code all contribute to this project. Follow the
[shared agent rules](../AGENTS.md) for AI-assisted work, the
[Code of Conduct](code-of-conduct.md), and the
[security reporting policy](security.md). Use the
[pull request template](pull_request_template.md) when submitting changes.
The [agent context](../docs/agent-context.md#build-test-and-lint-commands)
records this repository's commands and known limitations.

The triage rubric, change classification, and template baseline are adapted
from the [apps contribution guide](https://github.com/8fold/site-mastering-the-mundane.apps/blob/15782c3d7c9671f997ac778818580d575cf83bf7/.github/contributing.md).
That source repository is private. This guide is the canonical local policy;
the source link records provenance, not a runtime dependency.

## Issue triage

### Impact categories

Use these labels as qualitative impact categories, ordered from highest to
lowest priority. Apply every category supported by the issue's scope; they
can overlap.

1. **Security/Accessibility** (`security/accessibility`).
2. **Broken** (`broken`): does not work as promised.
3. **Annoying** (`annoying`): does not work as promised but has a workaround,
   or could be improved.
4. **Refactor/Re-engineer** (`refactor/re-engineer`): a structure or
   behavior/contract change; see [Change classification](#change-classification).
5. **New by policy** (`new-by-policy`): a capability proposed or adopted as
   policy on record by a maintainer, or required by external policy or law.
6. **New user request** (`new-user-request`): a capability requested without
   policy authority and not yet adopted by a maintainer.

The last two categories form an escalation path. When a maintainer adopts an
outside request, record that adoption in the issue and add `new-by-policy`,
retaining `new-user-request` as evidence of outside demand. Authorship alone
does not establish policy; a maintainer's idea needs a recorded policy
proposal or adoption.

Consider the combined impact, not just the highest-ranked category. Do not
add unsupported labels to inflate priority. This is a qualitative rubric,
not RICE, numeric weights, a formula, or a deterministic queue order.
Prerequisites and verification limits also affect the recommendation.

Labels route attention; they do not prove a defect. Distinguish reported
behavior from reproduced or otherwise substantiated defects. Inability to
reproduce a report does not invalidate it. Use the private
[security reporting route](security.md) for potential vulnerabilities;
do not publish sensitive details in an issue or triage report.

### Focus, disposition, and migration

- `documentation` identifies work focused on writing, reviewing, organizing,
  or publishing documentation, including contributor policy and design
  rationale. It is not a priority modifier. Combine it with applicable impact
  categories; do not add it to every code issue that also needs documentation.
- `wontfix` means there are no current plans to address the issue.
- `tracking` records work whose implementation or next steps are not yet
  settled. Update or close it when a clear path forward is recorded.

Disposition labels are not impact categories. Exclude `wontfix` from active
work recommendations and explain why a tracking issue is or is not actionable.

Existing GitHub-default labels remain during migration. `bug` and
`enhancement` are historical descriptions, not substitutes for the impact
rubric. Assess the evidence before adding impact categories; do not
automatically map every `enhancement` to `new-by-policy` or
`new-user-request`. Do not remove or rename existing labels without approval.
If a rubric label is missing, report it and request its creation rather than
substituting an unrelated label.

### Procedure

When asked to audit the backlog, prioritize issues, or recommend what to work
on next:

1. **Read current evidence.** Retrieve all open issues, including every page,
   and relevant bodies, comments, labels, linked PRs, and dependencies.
   Distinguish open issues from pull requests. State the retrieval date and
   any missing pages, permissions, or other limits; do not call a partial
   retrieval a complete audit.
2. **Assess scope and readiness.** Separate active work, `wontfix`, and
   unresolved tracking items. Check claimed repository state against source
   or a proportionate reproduction where needed. Record prerequisites,
   blockers, and evidence of policy adoption.
3. **Recommend supported labels.** Apply the cumulative rubric, explaining
   additions or changes. An audit or recommendation is read-only unless the
   user authorizes changing labels, issue bodies, or disposition.
4. **Recommend the next increment.** Explain combined impact, readiness,
   dependencies, uncertainty, and the smallest useful scope. An unblocking
   prerequisite may come before a higher-impact blocked issue. Give
   alternatives where evidence does not establish a clear preference; do not
   manufacture scores to break ties.
5. **Preserve the decision boundary.** A recommendation does not authorize
   implementation, closing issues, or opening a PR. Follow the
   [human gates](../AGENTS.md#human-gates-and-git-workflow).

### Labels in consuming repositories

Labels are GitHub metadata; copying templates does not create them. With
GitHub CLI access to both repositories, clone the current label set:

```sh
gh label clone 8fold/php-architecture --repo OWNER/REPO
```

Replace `OWNER/REPO` with the intended consuming repository. The command adds
missing labels, skips matching names, and does not delete destination-only
labels. Do not add `--force` without approval to overwrite existing definitions.
The current source set includes retained migration labels as well as the rubric.
Report access failures rather than assuming labels were created.

## Change classification

A **refactoring** changes internal structure while preserving observable
behavior and explicit contracts. Correct pre-existing tests of those
outcomes should still pass. Investigate failures as possible regressions,
incidental implementation coupling, or environment problems; do not change
expected outcomes simply to restore a passing suite.

Tests are evidence, not the definition of refactoring. Characterization tests
can establish existing behavior before restructuring. Test setup may change
when it pins incidental implementation, but behavioral expectations and
meaningful negative coverage must remain. Changing an explicit
developer-facing contract is re-engineering even when the change is made to
test setup.

A **re-engineering** changes observable behavior or an explicit
developer-facing contract, such as a public API or configuration, whether or
not internal structure also changes.

Keep each commit to one classification. A cohesive PR may contain several
commits of both kinds; classify each meaningful change in its body. Use
[Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/).
Explain material test, assertion, or contract changes and what was not
verified, including unrelated baseline failures.
