---
applyTo: ".github/ISSUE_TEMPLATE/*.md,.github/pull_request_template.md"
---

# GitHub templates

- Follow the [contribution and triage policy](../contributing.md).
- Keep issue-template YAML frontmatter valid. A template's purpose does not
  prove impact or policy adoption; do not assign impact labels automatically.
- Keep one default PR template at `.github/pull_request_template.md`.
  Specialized templates need a demonstrated workflow, not speculative checklists.
- Keep prompts reusable across consuming projects. Do not hard-code repository
  identities or assume website infrastructure. Adapt documented commands to
  the destination project's actual configuration.
- Put editor guidance in HTML comments so it does not render in submitted
  issues or PRs. Do not rely on a repository-relative Markdown link resolving
  from a submitted issue body; name the file or use an appropriate URL.
- Preserve the source/destination mapping in `bin/efqs` and the
  [reusable-file inventory](../../README.md#install-contribution-policy-and-templates)
  when adding, removing, or renaming distributed templates.
