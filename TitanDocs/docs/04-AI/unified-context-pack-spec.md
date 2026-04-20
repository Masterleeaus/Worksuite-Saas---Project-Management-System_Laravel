# Titan Unified Context Pack Spec

Defines the bounded context package assembled after multi-core review and before user-facing output, planning, or approval.

## Purpose
The Unified Context Pack (UCP) is the single trusted handoff object produced after:
- signal intake
- validation
- governance review
- sentinel readiness review
- specialist core reasoning
- cross-core critique
- weighted consolidation

No downstream summary, plan, or automation proposal should bypass this pack.

## Design Rules
- evidence first
- bounded size
- tenant scoped
- time scoped
- provenance attached
- contradictory signals preserved, not hidden
- uncertainty explicitly represented
- no speculative facts without a source tag

## Sections
### 1. Header
- ucp_id
- tenant_id
- pack_type
- generated_at
- expiry_at
- requested_by
- authority_profile
- confidence_score
- risk_score

### 2. Input Provenance
- source_signals[]
- source_tables[]
- source_manifests[]
- source_nodes[]
- external_model_calls[]
- file_refs[]
- channel_refs[]

Each provenance item should include:
- source_type
- source_id
- timestamp
- scope
- trust_weight
- freshness_weight

### 3. Situation Summary
A short factual reconstruction of current state.

Fields:
- domain
- current_objective
- active_entities[]
- active_constraints[]
- blocking_issues[]
- open_questions[]

### 4. Evidence Ledger
Evidence is stored as structured claims.

Per item:
- claim_id
- statement
- support_level
- evidence_refs[]
- conflicting_refs[]
- last_verified_at

## Contradiction Handling
Where sources disagree:
- preserve both claims
- assign confidence bands
- mark required follow-up checks
- prevent automatic execution when conflict touches money, permissions, or customer impact

## Output Artifacts
A UCP may generate:
- NarrativeSummary
- VisualisationSpec
- FinancialReport
- ProposedActions[]
- ReviewQuestions[]
- MissingEvidenceRequests[]

## Safety Gates
Automatic execution must be blocked when:
- confidence below threshold
- required evidence missing
- domain sentinel unresolved
- tenant scope ambiguous
- contradiction touches protected domains

## Retention
- retain lightweight pack metadata for audit
- optionally retain full pack snapshots by policy
- allow replay through Spool where enabled
