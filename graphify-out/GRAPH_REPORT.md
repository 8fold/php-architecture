# Graph Report - cp-joshbruce-shiny-giggle  (2026-11-02)

## Corpus Check
- 98 files · ~22,954 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 659 nodes · 983 edges · 57 communities (11 shown, 46 thin omitted)
- Extraction: 97% EXTRACTED · 3% INFERRED · 0% AMBIGUOUS · INFERRED: 32 edges (avg confidence: 0.9)
- Semantic-extraction usage: unavailable (the host did not expose token counts)

## Graph Freshness
- Built from commit: `ed3778cb`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Contributor and Agent Workflows
- Convention and Attribute Tests
- Composer Package Configuration
- Documentation CLI Regression Tests
- Boolean Analysis Rules
- Callable Classification Prototypes
- Experimental Callable Fixtures
- Attribute Inference Drafts
- Experimental Callable Evaluation
- PHPCS Method Access
- Attribute Declaration Contracts
- Mandatory Subclass Analysis
- Graph Refresh CLI Tests
- Mandatory Finality Analysis
- PHP Method Scope Glossary
- Advisory Finality Analysis
- Advisory Subclass Analysis
- Instruction Coverage Tests
- Compliant PHPCS Patterns
- Diagnostic Message Compilation
- Advisory Finality Tests
- Inheritance Diagnostic Templates
- Callable Return Types
- Nonboolean Negation Fixtures
- Callable Pattern Drafts
- Boolean Negation PHPStan Rule
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
- Community Conduct Policy
- Semantic Versioning Policy
- Finality Diagnostic Templates
- Noncompliant PHPCS Patterns
- Class Finality Instructions
- Private Security Disclosure
- Experimental Noncompliant Callables
- PHPUnit Authoring Conventions
- License and Warranty
- Finality Compliant Fixture
- Inheritance Compliant Fixture
- Message Compiler Fixture

## God Nodes (most connected - your core abstractions)
1. `EfqsDocsAgentsTest` - 39 edges
2. `CallableSubtype` - 26 edges
3. `php-architecture agent context` - 25 edges
4. `Convention workflow` - 21 edges
5. `MessageCompiler` - 19 edges
6. `CallablesMustBeTypedCompliant` - 18 edges
7. `NodeInspector` - 17 edges
8. `CallableEvaluators` - 13 edges
9. `PHPStanRule` - 13 edges
10. `PHPCSSniff` - 13 edges

## Surprising Connections (you probably didn't know these)
- `Measured versus unavailable extraction usage` --semantically_similar_to--> `Evidence-based completion`  [INFERRED] [semantically similar]
  docs/graphify-workflow.md → AGENTS.md
- `Class declaration and finality instruction` --conceptually_related_to--> `Custom PHPStan rules disabled by default`  [INFERRED]
  .github/instructions/php-class-definitions.instructions.md → docs/agent-context.md
- `PHP Class Use Instructions` --references--> `PHP Architecture Glossary`  [EXTRACTED]
  .github/instructions/php-class-use.instructions.md → docs/glossary.md
- `Bug report template` --conceptually_related_to--> `Evidence-based completion`  [INFERRED]
  .github/ISSUE_TEMPLATE/Bug_report.md → AGENTS.md
- `Issue tracking` --references--> `Convention request template`  [EXTRACTED]
  docs/convention-workflow.md → .github/ISSUE_TEMPLATE/Convention_request.md

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **SecurityContext across distinct authorization and pipeline roles** — docs_callable_pattern_drafts_guard, docs_callable_pattern_drafts_middleware, docs_callable_pattern_drafts_handler, docs_callable_pattern_drafts_voter [EXTRACTED 1.00]
- **Convention request and fixture-only PR authorization** — docs_convention_workflow_convention_request, docs_convention_workflow_fixture_draft, docs_convention_workflow_fixture_only_pr_exception, agents_human_gates [EXTRACTED 1.00]
- **Fixture, test, implementation, and activation approval flow** — docs_convention_workflow_fixture_draft, docs_convention_workflow_fixture_approval, docs_convention_workflow_tests, docs_convention_workflow_test_approval, docs_convention_workflow_implementation, docs_convention_workflow_activation_approval [EXTRACTED 1.00]
- **Convention layer assessment and selection** — docs_convention_workflow_layer_assessment, docs_convention_workflow_agent_instruction, docs_convention_workflow_phpcs_sniff, docs_convention_workflow_phpstan_rule, docs_convention_workflow_trait, docs_convention_workflow_interface, docs_convention_workflow_layer_selection_criteria [EXTRACTED 1.00]

## Communities (57 total, 46 thin omitted)

### Community 0 - "Contributor and Agent Workflows"
Cohesion: 0.05
Nodes (20): Contribution guide, Copilot session instructions, Analysis naming instruction, GitHub template maintenance instruction, Boolean expression instruction, PHPCS sniff development instruction, Prose documentation instruction, Bug report template (+12 more)

### Community 1 - "Convention and Attribute Tests"
Cohesion: 0.05
Nodes (12): CallablesMustBeTypedTest, CallableTypesTest, InstanceMethodsMustNotBeAccessedViaThisTest, ClassesMustBeFinalTest, ClassesMustNotBeSubclassTest, ClassesShouldNotBeSubclassTest, ClassesMustBeFinalNoncompliant, ClassesMustNotBeSubclassNoncompliant (+4 more)

### Community 2 - "Composer Package Configuration"
Cohesion: 0.04
Nodes (48): authors, autoload, autoload-dev, psr-4, psr-4, bin, description, license (+40 more)

### Community 4 - "Boolean Analysis Rules"
Cohesion: 0.06
Nodes (3): BooleanNegationMustBeExplicitTest, BooleanNegationMustBeExplicitCompliant, BooleanNegationMustBeExplicitNoncompliant

### Community 5 - "Callable Classification Prototypes"
Cohesion: 0.07
Nodes (17): CallableSubtypeInference, CallableSubtype, Assertion, Check, Command, CommandWithStatus, Factory, Handler (+9 more)

### Community 7 - "Attribute Inference Drafts"
Cohesion: 0.14
Nodes (5): AttributeInferer, {closure#1}(), {closure#2}(), {closure#3}(), NodeInspector

### Community 23 - "Callable Return Types"
Cohesion: 0.29
Nodes (6): Returns, BooleanType, NeverType, Other, SelfType, VoidType

### Community 37 - "Community Conduct Policy"
Cohesion: 0.40
Nodes (3): Contributor Covenant 2.1, Code of Conduct, Mozilla Code of Conduct Enforcement Ladder

### Community 38 - "Semantic Versioning Policy"
Cohesion: 0.50
Nodes (3): Versioning Policy, Semantic Versioning 2.0, Semantic Versioning 2.0 Specification

### Community 42 - "Class Finality Instructions"
Cohesion: 0.50
Nodes (3): Eightfold Attributes Classes BaseClass, Class declaration and finality instruction, Eightfold Attributes Classes Subclass

### Community 46 - "License and Warranty"
Cohesion: 0.67
Nodes (3): As-Is Warranty and Liability Disclaimer, Creative Commons Attribution-ShareAlike 4.0 International, 8fold PHP Architecture License

## Knowledge Gaps
- **65 isolated node(s):** `ClassesMustBeFinalCompliant`, `ClassesMustNotBeSubclassCompliant`, `BooleanType`, `NeverType`, `Other` (+60 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 324 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **46 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `CallableSubtype` connect `Callable Classification Prototypes` to `Experimental Callable Evaluation`, `Convention and Attribute Tests`, `Experimental Callable Fixtures`?**
  _High betweenness centrality (0.098) - this node is a cross-community bridge._
- **What connects `ClassesMustBeFinalCompliant`, `ClassesMustNotBeSubclassCompliant`, `BooleanType` to the rest of the system?**
  _65 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Contributor and Agent Workflows` be split into smaller, more focused modules?**
  _Cohesion score 0.05365296803652968 - nodes in this community are weakly interconnected._
- **Why does `EfqsDocsAgentsTest` connect `Documentation CLI Regression Tests` to `Convention and Attribute Tests`?**
  _High betweenness centrality (0.068) - this node is a cross-community bridge._
- **Should `Convention and Attribute Tests` be split into smaller, more focused modules?**
  _Cohesion score 0.051643192488262914 - nodes in this community are weakly interconnected._
- **Why does `NamedConstructor` connect `Experimental Callable Fixtures` to `Callable Classification Prototypes`?**
  _High betweenness centrality (0.045) - this node is a cross-community bridge._
- **Should `Composer Package Configuration` be split into smaller, more focused modules?**
  _Cohesion score 0.04081632653061224 - nodes in this community are weakly interconnected._