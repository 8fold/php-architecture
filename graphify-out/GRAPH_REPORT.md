# Graph Report - cp-joshbruce-shiny-giggle  (2026-11-01)

## Corpus Check
- 55 files · ~13,776 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 423 nodes · 572 edges · 30 communities (13 shown, 17 thin omitted)
- Extraction: 97% EXTRACTED · 3% INFERRED · 0% AMBIGUOUS · INFERRED: 18 edges (avg confidence: 0.84)
- Semantic-extraction usage: unavailable (the host did not expose token counts)

## Community Hubs (Navigation)
- Composer Package Metadata
- Callable Attribute Definitions
- CLI Documentation Tests
- PHP Class Conventions
- PHPCS Framework Dependencies
- Attribute Function Types
- Attribute Inference Engine
- Agent Startup and Context
- Test and Attribute Dependencies
- Request Authorization Flow
- Subclass Advisory Rule
- Analysis Naming Conventions
- PHPStan Finality Rule
- Finality Advisory Rule
- Subclass Prohibition Rule
- Compliant PHPCS Fixture
- Finality Rule Closures
- Contributor Conduct Policy
- PHPCS Sniff Development
- Semantic Versioning Policy
- Noncompliant PHPCS Fixture
- Security Disclosure Policy
- PHPUnit Test Conventions
- License and Warranty
- Final Class Fixture
- Subclass Compliance Fixture
- Missing Contribution Guide
- Missing PR Template

## God Nodes (most connected - your core abstractions)
1. `EfqsDocsAgentsTest` - 35 edges
2. `CallableSubtype` - 19 edges
3. `NodeInspector` - 17 edges
4. `ClassMustNotBeSubclass` - 14 edges
5. `ClassMustBeFinal` - 13 edges
6. `InstanceMethodsMustNotBeAccessedViaThis` - 12 edges
7. `ClassShouldNotBeSubclass` - 11 edges
8. `ClassShouldBeFinal` - 11 edges
9. `scripts` - 10 edges
10. `scripts-descriptions` - 9 edges

## Surprising Connections (you probably didn't know these)
- `Knowledge Graph Startup Gate` --semantically_similar_to--> `Session Startup Gates`  [INFERRED] [semantically similar]
  .github/copilot-instructions.md → docs/agent-context.md
- `PHP Class Use Instructions` --references--> `PHP Architecture Glossary`  [EXTRACTED]
  .github/instructions/php-class-use.instructions.md → docs/glossary.md
- `Explicit Approval Before Graph Creation` --conceptually_related_to--> `Knowledge Graph Startup Gate`  [INFERRED]
  docs/graphify-setup.md → .github/copilot-instructions.md
- `Semantic Refresh of Code and Prose` --conceptually_related_to--> `Knowledge Graph Startup Gate`  [INFERRED]
  docs/graphify-workflow.md → .github/copilot-instructions.md
- `Method Scope Conventions for self, static, parent, and this` --conceptually_related_to--> `MethodScopeConventionRule`  [INFERRED]
  .github/instructions/php-class-use.instructions.md → README.md

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Tools enforced by composer check** — docs_agent_context_composer_check, docs_agent_context_phpcs_sniffs, docs_agent_context_phpstan_rules, docs_agent_context_phpunit_tests [EXTRACTED 1.00]
- **Local adaptations of shared rules** — docs_agent_context_local_graph_policy, docs_agent_context_missing_contribution_guide, docs_agent_context_missing_pr_template, docs_agent_context_information_hierarchy_link [EXTRACTED 1.00]
- **Copilot session start gates** — _github_copilot_instructions_knowledge_graph_startup_gate, _github_copilot_instructions_shared_rules_startup_gate, docs_agent_context_session_startup [INFERRED 0.85]

## Communities (30 total, 17 thin omitted)

### Community 0 - "Composer Package Metadata"
Cohesion: 0.04
Nodes (45): authors, autoload, autoload-dev, psr-4, psr-4, bin, description, license (+37 more)

### Community 1 - "Callable Attribute Definitions"
Cohesion: 0.05
Nodes (5): NamedConstructor, Predicate, StateTransformerThis, BaseClass, Subclass

### Community 3 - "PHP Class Conventions"
Cohesion: 0.07
Nodes (10): PHP Class Declaration and Finality Rules, PHP Class Use Instructions, Shared Agent–User Rules, Graphify Setup Guide, Graphify Workflow Guide, ClassMustBeFinalRule, DataObjectPurityRule, 8fold Architecture for PHP README (+2 more)

### Community 4 - "PHPCS Framework Dependencies"
Cohesion: 0.10
Nodes (3): InstanceMethodsMustNotBeAccessedViaThisSniff, InstanceMethodsMustNotBeAccessedViaThis, InstanceMethodsMustNotBeAccessedViaThisTest

### Community 5 - "Attribute Function Types"
Cohesion: 0.10
Nodes (22): CallableSubtype, Assertion, Check, Command, CommandWithStatus, Factory, Handler, Middleware (+14 more)

### Community 6 - "Attribute Inference Engine"
Cohesion: 0.14
Nodes (5): AttributeInferer, {closure#1}(), {closure#2}(), {closure#3}(), NodeInspector

### Community 7 - "Agent Startup and Context"
Cohesion: 0.10
Nodes (18): Copilot Instructions, php-architecture Agent Context, Canonical Home of Shared Rules, composer check, composer production, Efqs Coding Standard, Information Hierarchy External Link, InstanceMethodsMustNotBeAccessedViaThis Sniff (+10 more)

### Community 8 - "Test and Attribute Dependencies"
Cohesion: 0.17
Nodes (5): ClassMustBeFinalTest, ClassMustBeFinalNoncompliant, ClassMustNotBeSubclassNoncompliant, ClassShouldBeFinalNoncompliant, ClassShouldNotBeSubclassCompliantUsingAttribute

### Community 9 - "Request Authorization Flow"
Cohesion: 0.14
Nodes (9): AccountDashboardHandler, AdminRoleVoter, MfaVerificationMiddleware, StateTransformerThis, UserProfile, Vote, Abstain, Denied (+1 more)

### Community 12 - "Analysis Naming Conventions"
Cohesion: 0.27
Nodes (4): Analysis Naming Instructions, RFC 2119, RFC 2119 Keyword-Based Analysis Rule Naming, PHP Architecture Glossary

### Community 18 - "Contributor Conduct Policy"
Cohesion: 0.40
Nodes (3): Contributor Covenant 2.1, Code of Conduct, Mozilla Code of Conduct Enforcement Ladder

### Community 20 - "Semantic Versioning Policy"
Cohesion: 0.50
Nodes (3): Versioning Policy, Semantic Versioning 2.0, Semantic Versioning 2.0 Specification

### Community 24 - "License and Warranty"
Cohesion: 0.67
Nodes (3): As-Is Warranty and Liability Disclaimer, Creative Commons Attribution-ShareAlike 4.0 International, 8fold PHP Architecture License

## Knowledge Gaps
- **68 isolated node(s):** `ClassShouldBeFinalCompliantUsingAttribute`, `ClassMustBeFinalCompliant`, `ClassMustNotBeSubclassCompliant`, `ClassShouldNotBeSubclassCompliantUsingAttribute`, `UserProfile` (+63 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 198 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **17 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `EfqsDocsAgentsTest` connect `CLI Documentation Tests` to `PHPCS Framework Dependencies`?**
  _High betweenness centrality (0.051) - this node is a cross-community bridge._
- **Are the 3 inferred relationships involving `ClassMustNotBeSubclass` (e.g. with `.tip()` and `.errorFoundFor()`) actually correct?**
  _`ClassMustNotBeSubclass` has 3 INFERRED edges - model-reasoned connections that need verification._
- **What connects `ClassShouldBeFinalCompliantUsingAttribute`, `ClassMustBeFinalCompliant`, `ClassMustNotBeSubclassCompliant` to the rest of the system?**
  _68 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Composer Package Metadata` be split into smaller, more focused modules?**
  _Cohesion score 0.043478260869565216 - nodes in this community are weakly interconnected._
- **Why does `ClassMustNotBeSubclass` connect `Subclass Prohibition Rule` to `Test and Attribute Dependencies`, `PHPStan Inheritance Analysis`, `Subclass Advisory Rule`, `PHPStan Finality Rule`, `Finality Advisory Rule`?**
  _High betweenness centrality (0.015) - this node is a cross-community bridge._
- **Should `Callable Attribute Definitions` be split into smaller, more focused modules?**
  _Cohesion score 0.05128205128205128 - nodes in this community are weakly interconnected._
- **Should `CLI Documentation Tests` be split into smaller, more focused modules?**
  _Cohesion score 0.058823529411764705 - nodes in this community are weakly interconnected._
