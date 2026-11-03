—
applyTo: “**/*.md”
—

# Prose Documentation Instructions

These rules govern Markdown prose editing across the repository. Rules defined elsewhere are linked, not restated.

- **Voice:** Follow [Voice](../../AGENTS.md#voice).
- **Placement:** Give each fact one canonical home and link to it ([Information placement](../../AGENTS.md#information-placement)).
- **Terms:** Use PHP terms as defined in the [glossary](../../docs/glossary.md).
- **Source Line Breaks:** Do not impose a fixed line-length limit. Use semantic line breaks where they improve diffs and review; rendered wrapping depends on the viewer.
- **Relative & Immutable Links:** Use relative links for files within this repository. Use commit permalinks (SHA-based) for external repository links to prevent target drift.
- **Generated Markdown Exclusions:** Do not hand-edit generated Markdown files (e.g., `graphify-out/GRAPH_REPORT.md`) unless a documented workflow gate explicitly requires it.
