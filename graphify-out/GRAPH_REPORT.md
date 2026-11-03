# Graph Report - cp-joshbruce-fluffy-funicular  (2026-11-02)

## Corpus Check
- 110 files · ~24,936 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 873 nodes · 1219 edges · 94 communities (15 shown, 79 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 11 edges (avg confidence: 0.9)
- Semantic-extraction token usage: unavailable (not measured by this host).

## Community Hubs (Navigation)
- Convention Workflow Guidance
- Stringable Contract Fixtures
- Inherited Stringability Fixtures
- Callable Attribute Contracts
- EFQS Documentation Commands
- Contribution and Triage
- PHP Node Inspection
- Callable Typing Fixtures
- Graphify Startup Workflow
- Class Attribute Tests
- PHPUnit Production Tests
- Callable Analysis Components
- PHP Terms and Documentation
- PHPCS Instance Method Sniff
- Advisory Finality Rule
- Composer Package Metadata
- Composer Quality Scripts
- PHPStan Rule Tests
- Graph Refresh Tests
- Compliant Boolean Expressions
- Composer Script Descriptions
- Boolean Negation Rule Tests
- Analysis Message Compiler
- Noncompliant Boolean Expressions
- Instruction Coverage Tests
- Stringable Convention Tests
- Compliant Instance Method Access
- Instance Method Sniff Tests
- Mandatory Finality Rule
- Prohibited Subclass Rule
- Advisory Inheritance Rule
- Mandatory Finality Tests
- Advisory Finality Tests
- Convention Request Template
- Semantic Release Versioning
- Callable Architecture Patterns
- Named Constructor Examples
- NonBoolean Comparison Fixtures
- Analysis Naming Conventions
- Explicit Boolean Expressions
- Bug Report Template
- Feature Request Template
- Composer Autoload Mapping
- Composer Runtime Dependencies
- Stringable Rule Implementation
- Predicate Callable Contract
- Callable This Transformer
- Accessor Callable Contract
- Command Callable Contract
- Status Command Callable
- Factory Callable Contract
- Pure Callable Contract
- State Transformer Contract
- Function This Transformer
- Advisory Subclass Tests
- Deprecation Issue Template
- Callable Inference Prototype
- Boolean Negation Rule
- Noncompliant Instance Access
- Production Source Integrity
- Architecture Package License
- Noncompliant Callable Fixtures
- Inheritance Restriction Remedies
- Development Autoload Mapping
- Advisory Finality Guidance
- Advisory Inheritance Guidance
- Boolean Negation Remedies
- Boolean Negation Diagnostic
- Mandatory Finality Remedy
- Mandatory Finality Diagnostic
- Mandatory Inheritance Diagnostic
- Advisory Finality Diagnostic
- Advisory Inheritance Diagnostic
- Instance Method Access Diagnostic
- Message Template Placeholders
- Final Class Compliant Fixtures
- Subclass Compliant Fixtures

## God Nodes (most connected - your core abstractions)
1. `EfqsDocsAgentsTest` - 39 edges
2. `CallableSubtype` - 26 edges
3. `MessageCompiler` - 19 edges
4. `CallablesMustBeTypedCompliant` - 18 edges
5. `NodeInspector` - 17 edges
6. `Contribution and triage policy` - 17 edges
7. `ToString` - 15 edges
8. `Convention workflow` - 15 edges
9. `PHP architecture agent context` - 14 edges
10. `CallableEvaluators` - 13 edges

## Surprising Connections (you probably didn't know these)
- `Advisory class finality rules` --semantically_similar_to--> `Guidance is not enforcement`  [INFERRED] [semantically similar]
  .github/instructions/php-class-definitions.instructions.md → AGENTS.md
- `PHPCS fixture style exclusion` --semantically_similar_to--> `PHPStan fixture exclusion`  [INFERRED] [semantically similar]
  .github/instructions/phpcs-sniff-development.instructions.md → docs/agent-context.md
- `Valid red test evidence` --semantically_similar_to--> `Completion evidence`  [INFERRED] [semantically similar]
  .github/instructions/phpunit-test-definitions.instructions.md → AGENTS.md
- `ClassesImplementingStringableMustImplementToString convention` --conceptually_related_to--> `Stringability rule activation`  [INFERRED]
  src/Analysis/ClassesImplementingStringableMustImplementToString/help.md → docs/agent-context.md
- `Copilot startup gates` --references--> `PHP architecture agent context`  [EXTRACTED]
  .github/copilot-instructions.md → docs/agent-context.md

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Cumulative triage impact categories** — _github_contributing_security_accessibility, _github_contributing_broken, _github_contributing_annoying, _github_contributing_new_by_policy, _github_contributing_new_user_request [EXTRACTED 1.00]
- **Callable pattern taxonomy** — docs_callable_pattern_drafts_guard, docs_callable_pattern_drafts_middleware, docs_callable_pattern_drafts_handler, docs_callable_pattern_drafts_voter, docs_callable_pattern_drafts_property_hooks [EXTRACTED 1.00]
- **Class stringability contract** — _github_instructions_php_class_stringability_instructions_effective_class_stringability, _github_instructions_php_class_stringability_instructions_native_stringable_interface, _github_instructions_php_class_stringability_instructions_native_magic_string_conversion, _github_instructions_php_class_stringability_instructions_eightfold_interfaces_tostring, _github_instructions_php_class_stringability_instructions_suite_tostring_method [EXTRACTED 1.00]
- **PHPCS gated development** — _github_instructions_phpcs_sniff_development_instructions_phpcs_fixtures, _github_instructions_phpcs_sniff_development_instructions_fixture_approval, _github_instructions_phpcs_sniff_development_instructions_isolated_phpcs_sniff_tests, _github_instructions_phpcs_sniff_development_instructions_test_approval, _github_instructions_phpcs_sniff_development_instructions_phpcs_sniff_implementation, _github_instructions_phpcs_sniff_development_instructions_activation_approval, _github_instructions_phpcs_sniff_development_instructions_phpcs_ruleset_activation [EXTRACTED 1.00]
- **Convention implementation layers** — docs_convention_workflow_agent_instruction_layer, docs_convention_workflow_phpcs_token_analysis, docs_convention_workflow_phpstan_semantic_analysis, docs_convention_workflow_trait_and_interface_layers [EXTRACTED 1.00]

## Communities (94 total, 79 thin omitted)

### Community 0 - "Convention Workflow Guidance"
Cohesion: 0.05
Nodes (30): BaseClass attribute, Class definition instructions, Subclass attribute, Class stringability instructions, Eightfold\Interfaces\ToString, Native magic string conversion, Native Stringable interface, Suite toString method (+22 more)

### Community 1 - "Stringable Contract Fixtures"
Cohesion: 0.06
Nodes (26): ToString, AbstractSuiteStringable, AliasedSuiteStringable, compliantAnonymousStringable(), FullyQualifiedSuiteStringable, ImportedSuiteStringable, NativeStringabilityDeclaration, NativeThenSuiteStringable (+18 more)

### Community 2 - "Inherited Stringability Fixtures"
Cohesion: 0.07
Nodes (22): DeclaresMagicAndDerivedSuiteContract, DerivedNativeContract, DerivedSuiteContract, ImplementsDerivedSuiteContract, InheritsSuiteContract, NativeAndDerivedSuiteContract, OrdinaryChild, OrdinaryParent (+14 more)

### Community 3 - "Callable Attribute Contracts"
Cohesion: 0.06
Nodes (23): CallableSubtypeInference, CallableSubtype, Assertion, Check, Command, CommandWithStatus, Factory, Handler (+15 more)

### Community 5 - "Contribution and Triage"
Cohesion: 0.06
Nodes (5): Contributor Covenant, Contribution and triage policy, GitHub template guidance, Pull request template, Security reporting policy

### Community 6 - "PHP Node Inspection"
Cohesion: 0.14
Nodes (5): AttributeInferer, {closure#1}(), {closure#2}(), {closure#3}(), NodeInspector

### Community 8 - "Graphify Startup Workflow"
Cohesion: 0.09
Nodes (3): Copilot startup gates, Graphify setup, Graphify refresh workflow

### Community 9 - "Class Attribute Tests"
Cohesion: 0.14
Nodes (6): BaseClass, Subclass, ClassesMustNotBeSubclassNoncompliant, ClassesShouldBeFinalCompliantUsingAttribute, ClassesShouldBeFinalNoncompliant, ClassesShouldNotBeSubclassCompliantUsingAttribute

### Community 12 - "PHP Terms and Documentation"
Cohesion: 0.11
Nodes (3): Method and property scope, Prose documentation guidance, PHP glossary

### Community 15 - "Composer Package Metadata"
Cohesion: 0.17
Nodes (11): authors, bin, description, license, name, require-dev, phpunit/phpunit, script-aliases (+3 more)

### Community 16 - "Composer Quality Scripts"
Cohesion: 0.17
Nodes (12): scripts, check, check:analyze, check:instructions, check:style, check:test, graph:code, graph:refresh (+4 more)

### Community 20 - "Composer Script Descriptions"
Cohesion: 0.18
Nodes (11): scripts-descriptions, check, check:analyze, check:instructions, check:style, check:test, graph:code, graph:refresh (+3 more)

### Community 43 - "Composer Autoload Mapping"
Cohesion: 0.33
Nodes (6): autoload, psr-4, Eightfold\\Analysis\\, Eightfold\\Attributes\\, Eightfold\\Efqs\\, Eightfold\\Interfaces\\

### Community 44 - "Composer Runtime Dependencies"
Cohesion: 0.33
Nodes (6): require, 8fold/php-html-builder, php, phpstan/phpstan, spaze/phpstan-disallowed-calls, squizlabs/php_codesniffer

### Community 67 - "Development Autoload Mapping"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Eightfold\\Tests\\

## Knowledge Gaps
- **71 isolated node(s):** `ClassesMustBeFinalCompliant`, `ClassesMustNotBeSubclassCompliant`, `Assertion`, `Check`, `Command` (+66 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 488 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **79 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `CallableSubtype` connect `Callable Attribute Contracts` to `Class Attribute Tests`, `PHPUnit Production Tests`, `Callable Analysis Components`, `Callable Typing Fixtures`?**
  _High betweenness centrality (0.057) - this node is a cross-community bridge._
- **What connects `ClassesMustBeFinalCompliant`, `ClassesMustNotBeSubclassCompliant`, `Assertion` to the rest of the system?**
  _71 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Convention Workflow Guidance` be split into smaller, more focused modules?**
  _Cohesion score 0.05487269534679543 - nodes in this community are weakly interconnected._
- **Why does `EfqsDocsAgentsTest` connect `EFQS Documentation Commands` to `PHPUnit Production Tests`?**
  _High betweenness centrality (0.047) - this node is a cross-community bridge._
- **Should `Stringable Contract Fixtures` be split into smaller, more focused modules?**
  _Cohesion score 0.058673469387755105 - nodes in this community are weakly interconnected._
- **Why does `ToString` connect `Stringable Contract Fixtures` to `Inherited Stringability Fixtures`, `PHPStan Rule Implementations`?**
  _High betweenness centrality (0.030) - this node is a cross-community bridge._
- **Should `Inherited Stringability Fixtures` be split into smaller, more focused modules?**
  _Cohesion score 0.07051282051282051 - nodes in this community are weakly interconnected._

## Refresh coverage audit

- Incremental refresh: 12 changed code files and 10 changed documents extracted; no changed documents were left unextracted.
- Parser warning: `bin/efqs` yields no modeled symbols; its top-level executable behavior is not fully represented.
- Graph health warning: 7 self-loop edges; no missing or dangling endpoints were detected in the merged graph.
