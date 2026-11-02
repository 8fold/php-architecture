# Graph Report - cp-joshbruce-shiny-giggle  (2026-11-02)

## Corpus Check
- 98 files · ~22,653 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 634 nodes · 926 edges · 56 communities (11 shown, 45 thin omitted)
- Extraction: 97% EXTRACTED · 3% INFERRED · 0% AMBIGUOUS · INFERRED: 26 edges (avg confidence: 0.9)
- Semantic-extraction usage: unavailable (the host did not expose token counts)

## Graph Freshness
- Built from commit: `f20e45bd`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Experimental Callable Evaluation
- Contributor and Agent Workflows
- Composer Package Configuration
- Callable Classification Prototypes
- Documentation CLI Regression Tests
- Boolean Analysis Rules
- Experimental Callable Fixtures
- Attribute Inference Drafts
- Attribute and Autoload Contracts
- Convention Test Dependencies
- PHPCS Method Access
- Attribute Declaration Contracts
- Graph Refresh CLI Tests
- PHP Method Scope Glossary
- Instruction Coverage Tests
- Compliant PHPCS Patterns
- PHPCS Diagnostic Tests
- Advisory Finality Tests
- Inheritance Diagnostic Templates
- Callable Return Types
- Nonboolean Negation Fixtures
- Named Constructor Metadata
- Predicate Attribute Metadata
- Callable Fluent Mutation Metadata
- Accessor Attribute Metadata
- Command Attribute Metadata
- Status Command Metadata
- Factory Attribute Metadata
- Pure Attribute Metadata
- Immutable Transformation Metadata
- Function Fluent Mutation Metadata
- Advisory Inheritance Tests
- Community Conduct Policy
- Semantic Versioning Policy
- Finality Diagnostic Templates
- Noncompliant PHPCS Patterns
- Mandatory Finality Tests
- Mandatory Inheritance Tests
- Production Source Integrity
- Private Security Disclosure
- Experimental Callable Rule Tests
- Experimental Noncompliant Callables
- PHPUnit Authoring Conventions
- License and Warranty
- Finality Compliant Fixture
- Inheritance Compliant Fixture
- Message Compiler Fixture

## God Nodes (most connected - your core abstractions)
1. `EfqsDocsAgentsTest` - 39 edges
2. `CallableSubtype` - 26 edges
3. `MessageCompiler` - 19 edges
4. `CallablesMustBeTypedCompliant` - 18 edges
5. `NodeInspector` - 17 edges
6. `CallableEvaluators` - 13 edges
7. `PHPStanRule` - 13 edges
8. `PHPCSSniff` - 13 edges
9. `GraphCodeRefreshTest` - 13 edges
10. `BooleanNegationMustBeExplicitCompliant` - 13 edges

## Surprising Connections (you probably didn't know these)
- `Measured versus unavailable extraction usage` --semantically_similar_to--> `Evidence-based completion`  [INFERRED] [semantically similar]
  docs/graphify-workflow.md → AGENTS.md
- `PHP Class Use Instructions` --references--> `PHP Architecture Glossary`  [EXTRACTED]
  .github/instructions/php-class-use.instructions.md → docs/glossary.md
- `Bug report template` --conceptually_related_to--> `Evidence-based completion`  [INFERRED]
  .github/ISSUE_TEMPLATE/Bug_report.md → AGENTS.md
- `Deprecation notice or request template` --conceptually_related_to--> `Human-controlled authorization gates`  [INFERRED]
  .github/ISSUE_TEMPLATE/Deprecation_notice_or_request.md → AGENTS.md
- `Efqs reusable-file copy commands` --references--> `Deprecation notice or request template`  [EXTRACTED]
  README.md → .github/ISSUE_TEMPLATE/Deprecation_notice_or_request.md

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Five-layer convention assessment tracked in one issue** — docs_convention_workflow_document, _github_issue_template_convention_request_document, docs_convention_workflow_agent_instruction, docs_convention_workflow_phpcs_sniff, docs_convention_workflow_phpstan_rule, docs_convention_workflow_trait, docs_convention_workflow_interface [EXTRACTED 1.00]
- **SecurityContext across distinct authorization and pipeline roles** — docs_callable_pattern_drafts_guard, docs_callable_pattern_drafts_middleware, docs_callable_pattern_drafts_handler, docs_callable_pattern_drafts_voter [EXTRACTED 1.00]

## Communities (56 total, 45 thin omitted)

### Community 0 - "Experimental Callable Evaluation"
Cohesion: 0.05
Nodes (8): CallableEvaluators, PHPStanRule, PHPStanRule, PHPStanRule, PHPStanRule, PHPStanRule, MessageCompiler, MessageCompilerTest

### Community 1 - "Contributor and Agent Workflows"
Cohesion: 0.06
Nodes (24): Contribution guide, Copilot session instructions, Analysis naming instruction, GitHub template maintenance instruction, Boolean expression instruction, Eightfold Attributes Classes BaseClass, Class declaration and finality instruction, Eightfold Attributes Classes Subclass (+16 more)

### Community 2 - "Composer Package Configuration"
Cohesion: 0.04
Nodes (48): authors, autoload, autoload-dev, psr-4, psr-4, bin, description, license (+40 more)

### Community 3 - "Callable Classification Prototypes"
Cohesion: 0.06
Nodes (17): CallableSubtypeInference, CallableSubtype, Assertion, Check, Command, CommandWithStatus, Factory, Handler (+9 more)

### Community 5 - "Boolean Analysis Rules"
Cohesion: 0.06
Nodes (3): BooleanNegationMustBeExplicitTest, BooleanNegationMustBeExplicitCompliant, BooleanNegationMustBeExplicitNoncompliant

### Community 7 - "Attribute Inference Drafts"
Cohesion: 0.14
Nodes (5): AttributeInferer, {closure#1}(), {closure#2}(), {closure#3}(), NodeInspector

### Community 9 - "Convention Test Dependencies"
Cohesion: 0.19
Nodes (5): ClassesMustBeFinalNoncompliant, ClassesMustNotBeSubclassNoncompliant, ClassesShouldBeFinalCompliantUsingAttribute, ClassesShouldBeFinalNoncompliant, ClassesShouldNotBeSubclassCompliantUsingAttribute

### Community 19 - "Callable Return Types"
Cohesion: 0.29
Nodes (6): Returns, BooleanType, NeverType, Other, SelfType, VoidType

### Community 32 - "Community Conduct Policy"
Cohesion: 0.40
Nodes (3): Contributor Covenant 2.1, Code of Conduct, Mozilla Code of Conduct Enforcement Ladder

### Community 33 - "Semantic Versioning Policy"
Cohesion: 0.50
Nodes (3): Versioning Policy, Semantic Versioning 2.0, Semantic Versioning 2.0 Specification

### Community 44 - "License and Warranty"
Cohesion: 0.67
Nodes (3): As-Is Warranty and Liability Disclaimer, Creative Commons Attribution-ShareAlike 4.0 International, 8fold PHP Architecture License

## Knowledge Gaps
- **65 isolated node(s):** `BooleanType`, `NeverType`, `Other`, `SelfType`, `VoidType` (+60 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 322 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **45 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `CallableSubtype` connect `Callable Classification Prototypes` to `Experimental Callable Evaluation`, `Attribute and Autoload Contracts`, `Experimental Callable Fixtures`?**
  _High betweenness centrality (0.105) - this node is a cross-community bridge._
- **What connects `BooleanType`, `NeverType`, `Other` to the rest of the system?**
  _65 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Experimental Callable Evaluation` be split into smaller, more focused modules?**
  _Cohesion score 0.051582278481012656 - nodes in this community are weakly interconnected._
- **Why does `EfqsDocsAgentsTest` connect `Documentation CLI Regression Tests` to `Attribute and Autoload Contracts`?**
  _High betweenness centrality (0.073) - this node is a cross-community bridge._
- **Should `Contributor and Agent Workflows` be split into smaller, more focused modules?**
  _Cohesion score 0.0632996632996633 - nodes in this community are weakly interconnected._
- **Why does `NamedConstructor` connect `Experimental Callable Fixtures` to `Callable Classification Prototypes`?**
  _High betweenness centrality (0.049) - this node is a cross-community bridge._
- **Should `Composer Package Configuration` be split into smaller, more focused modules?**
  _Cohesion score 0.04081632653061224 - nodes in this community are weakly interconnected._