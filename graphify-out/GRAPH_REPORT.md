# Graph Report - cp-joshbruce-shiny-telegram  (2026-11-01)

## Corpus Check
- 57 files · ~14,835 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 391 nodes · 555 edges · 20 communities (11 shown, 9 thin omitted)
- Extraction: 98% EXTRACTED · 2% INFERRED · 0% AMBIGUOUS · INFERRED: 11 edges (avg confidence: 0.76)
- Token cost: unavailable (extracted by a Copilot agent; usage appears in the Copilot CLI output)

## Community Hubs (Navigation)
- Composer and PHPStan Dependencies
- Attributes and Test Base
- Docs Agents Test
- Agent Context and Composer Config
- AGENTS Rules and PHPCS Sniff
- Callable Subtype Tests
- PHPStan Node Inspector
- Inference and Attribute Inferer
- Handler Examples and Rule Interfaces
- PHPCS Sniff Implementation
- Class Finality Instructions
- PHPCS Sniff Tests
- Glossary and Composer Scripts
- PHPCS Compliant Fixture
- Graph CLI Script
- PHPCS Noncompliant Fixture
- License and Conduct
- ClassMustBeFinal Fixtures
- ClassMustNotBeSubclass Fixtures

## God Nodes (most connected - your core abstractions)
1. `EfqsDocsAgentsTest` - 35 edges
2. `CallableSubtype` - 19 edges
3. `NodeInspector` - 17 edges
4. `ClassMustNotBeSubclass` - 14 edges
5. `ClassMustBeFinal` - 13 edges
6. `scripts` - 12 edges
7. `InstanceMethodsMustNotBeAccessedViaThis` - 12 edges
8. `AGENTS.md shared agent-user rules` - 12 edges
9. `scripts-descriptions` - 11 edges
10. `ClassShouldBeFinal` - 11 edges

## Surprising Connections (you probably didn't know these)
- `MethodScopeConventionRule` --semantically_similar_to--> `PHP class use instructions`  [INFERRED] [semantically similar]
  README.md → .github/instructions/php-class-use.instructions.md
- `Semantic Versioning 2.0 (zero-major)` --semantically_similar_to--> `Refactoring vs re-engineering commits`  [INFERRED] [semantically similar]
  .github/versioning.md → AGENTS.md
- `ClassMustBeFinalRule` --semantically_similar_to--> `AllowInheritance attribute and class finality`  [INFERRED] [semantically similar]
  README.md → .github/instructions/php-class-definitions.instructions.md
- `Prose documentation instructions` --references--> `Glossary`  [EXTRACTED]
  .github/instructions/prose-documentation.instructions.md → docs/glossary.md
- `Security Policy` --conceptually_related_to--> `Security rules (no secrets exposure)`  [INFERRED]
  .github/security.md → AGENTS.md

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Graphify setup, workflow, and copy commands** — docs_graphify_setup, docs_graphify_workflow, readme_efqs_docs_commands [EXTRACTED 1.00]
- **Path-scoped Copilot instruction files** — github_instructions_analysis_naming_instructions, github_instructions_php_class_definitions_instructions, github_instructions_php_class_use_instructions, github_instructions_phpcs_sniff_development_instructions, github_instructions_phpunit_test_definitions_instructions, github_instructions_prose_documentation_instructions [EXTRACTED 1.00]
- **Binding agent rules documents** — agents, docs_agent_context, github_copilot_instructions [EXTRACTED 1.00]

## Communities (20 total, 9 thin omitted)

### Community 0 - "Composer and PHPStan Dependencies"
Cohesion: 0.07
Nodes (4): ClassMustBeFinal, ClassShouldBeFinal, ClassShouldBeFinalTest, ClassShouldBeFinalCompliantUsingAttribute

### Community 1 - "Attributes and Test Base"
Cohesion: 0.08
Nodes (9): ClassMustNotBeSubclass, ClassShouldNotBeSubclass, ClassMustBeFinalTest, ClassMustNotBeSubclassTest, ClassShouldNotBeSubclassTest, ClassMustBeFinalNoncompliant, ClassMustNotBeSubclassNoncompliant, ClassShouldBeFinalNoncompliant (+1 more)

### Community 2 - "Docs Agents Test"
Cohesion: 0.05
Nodes (5): NamedConstructor, Predicate, StateTransformerThis, BaseClass, Subclass

### Community 3 - "Agent Context and Composer Config"
Cohesion: 0.05
Nodes (38): authors, autoload, autoload-dev, psr-4, psr-4, bin, description, license (+30 more)

### Community 4 - "AGENTS Rules and PHPCS Sniff"
Cohesion: 0.09
Nodes (36): AGENTS.md shared agent-user rules, Communication rules, Evidence and completion rules, Human gates and Git workflow, Information placement (one home per fact), Navigation, tools, and validation, Refactoring vs re-engineering commits, Security rules (no secrets exposure) (+28 more)

### Community 6 - "PHPStan Node Inspector"
Cohesion: 0.10
Nodes (22): CallableSubtype, Assertion, Check, Command, CommandWithStatus, Factory, Handler, Middleware (+14 more)

### Community 7 - "Inference and Attribute Inferer"
Cohesion: 0.14
Nodes (5): AttributeInferer, {closure#1}(), {closure#2}(), {closure#3}(), NodeInspector

### Community 8 - "Handler Examples and Rule Interfaces"
Cohesion: 0.14
Nodes (9): AccountDashboardHandler, AdminRoleVoter, MfaVerificationMiddleware, StateTransformerThis, UserProfile, Vote, Abstain, Denied (+1 more)

### Community 10 - "Class Finality Instructions"
Cohesion: 0.18
Nodes (12): Glossary, Method terms (Function, Method, Instance Method, Static Method), Property terms (Instance Property, Static Property), AllowInheritance attribute and class finality, Class declaration and finality instructions, PHP class use instructions, README: 8fold Architecture for PHP, ClassMustBeFinalRule (+4 more)

### Community 12 - "Glossary and Composer Scripts"
Cohesion: 0.18
Nodes (11): scripts-descriptions, check, check:analyze, check:style, check:test, graph, graph:code, graph:docs (+3 more)

### Community 14 - "Graph CLI Script"
Cohesion: 0.67
Nodes (5): fail(), findCommand(), modifiedSince(), refreshCode(), refreshDocs()

## Knowledge Gaps
- **74 isolated node(s):** `Prose documentation`, `name`, `description`, `license`, `authors` (+69 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 180 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **9 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `EfqsDocsAgentsTest` connect `Callable Subtype Tests` to `PHPCS Sniff Tests`?**
  _High betweenness centrality (0.060) - this node is a cross-community bridge._
- **Are the 3 inferred relationships involving `ClassMustNotBeSubclass` (e.g. with `.tip()` and `.errorFoundFor()`) actually correct?**
  _`ClassMustNotBeSubclass` has 3 INFERRED edges - model-reasoned connections that need verification._
- **What connects `Prose documentation`, `name`, `description` to the rest of the system?**
  _74 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Composer and PHPStan Dependencies` be split into smaller, more focused modules?**
  _Cohesion score 0.06717687074829932 - nodes in this community are weakly interconnected._
- **Why does `InstanceMethodsMustNotBeAccessedViaThisSniff` connect `PHPCS Sniff Implementation` to `PHPCS Sniff Tests`?**
  _High betweenness centrality (0.025) - this node is a cross-community bridge._
- **Should `Attributes and Test Base` be split into smaller, more focused modules?**
  _Cohesion score 0.08130081300813008 - nodes in this community are weakly interconnected._
- **Why does `ClassMustNotBeSubclass` connect `Attributes and Test Base` to `Composer and PHPStan Dependencies`?**
  _High betweenness centrality (0.017) - this node is a cross-community bridge._