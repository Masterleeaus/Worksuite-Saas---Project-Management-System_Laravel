# Titan Signal Lifecycle and Rejection Taxonomy

Defines how a signal moves from origin to domain readiness, how failures are recorded, and how retries and rejections are handled.

## Purpose

The signal lifecycle exists to prevent silent execution, preserve auditability, enforce tenant safety, and create a deterministic path from observation to approved domain action.

A signal is never considered executable merely because it exists. It must move through validation, governance, and domain approval before any downstream domain service may consume it.

## Canonical Lifecycle States

A signal progresses through these states:

- `process` — signal has been originated by a source, scout, user action, sync replay, importer, or bridge.
- `processing` — SignalAI validation is actively evaluating structure, schema compliance, idempotency, tenant fence, and basic logic.
- `processed` — AEGIS governance has accepted the signal as policy-valid and safe to present for domain approval.
- `approved` — Sentinel has verified domain readiness and downstream services may consume the signal.
- `rejected` — the signal cannot proceed in its current form.
- `deferred` — the signal may proceed later after dependencies, timing, or required data become available.
- `expired` — the signal has aged past its validity window.
- `cancelled` — the signal was intentionally withdrawn or superseded.

## Stage Ownership

### 1. Origination layer
Responsible actor examples:
- Scout
- User interface event
- API bridge
- Sync replay engine
- Import pipeline
- Scheduled automation

Responsibilities:
- stamp origin metadata
- attach tenant and scope
- include minimal intent and payload
- declare dependencies if known
- assign initial priority and expiry

### 2. SignalAI layer
Responsible for structural and logical validation.

SignalAI must check:
- required field presence
- manifest/schema compliance
- tenant boundary validity
- idempotency key integrity
- duplicate or replay detection
- invalid field combinations
- malformed dependencies
- impossible state transitions detectable at schema/logic level

### 3. AEGIS layer
Responsible for governance validation.

AEGIS must check:
- permission and actor authority
- policy compliance
- cross-domain contradictions
- quota/limit controls
- financial or legal control constraints
- automation confidence thresholds
- approval requirements for sensitive actions

### 4. Sentinel layer
Responsible for domain readiness.

Sentinel must check:
- required dependency completion
- domain object existence and current state
- scheduling conflicts
- resource/capacity availability
- lock states and temporal constraints
- prerequisite approvals or documents
- domain veto conditions

## Allowed State Transitions

Allowed transitions:

- `process -> processing`
- `processing -> processed`
- `processing -> rejected`
- `processing -> deferred`
- `processed -> approved`
- `processed -> rejected`
- `processed -> deferred`
- `deferred -> processing`
- `deferred -> processed`
- `deferred -> rejected`
- `approved -> cancelled` only by governance or explicit reversal policy
- any non-terminal state -> `expired` when expiry is reached

Disallowed transitions:
- direct `process -> approved`
- direct `process -> processed`
- direct `processing -> approved`
- downstream services changing a signal back to `processed`

## Rejection Model

Every rejection must contain:
- `rejected_at`
- `rejected_by_layer`
- `rejection_code`
- `rejection_message`
- `retryable` boolean
- `correction_hint`
- `blocking_dependencies` if applicable

## Rejection Code Families

### SignalAI rejection codes
- `schema_missing_field`
- `schema_invalid_type`
- `schema_unknown_manifest`
- `tenant_missing`
- `tenant_mismatch`
- `idempotency_collision`
- `duplicate_signal`
- `invalid_scope`
- `invalid_transition`
- `payload_incoherent`

### AEGIS rejection codes
- `permission_denied`
- `policy_violation`
- `cross_domain_conflict`
- `quota_exceeded`
- `financial_guard_failed`
- `approval_required`
- `risk_threshold_exceeded`
- `automation_not_permitted`
- `compliance_hold`

### Sentinel rejection codes
- `missing_dependency`
- `resource_unavailable`
- `scheduling_conflict`
- `domain_state_invalid`
- `lock_active`
- `prerequisite_missing`
- `readiness_failed`
- `domain_veto`
- `time_window_closed`

## Deferred Model

A signal should be deferred rather than rejected when the signal is structurally valid but cannot yet be approved due to conditions likely to change.

Examples:
- waiting for customer confirmation
- waiting for approval of a quote
- waiting for a technician to become available
- waiting for an invoice to post
- waiting for another signal to be approved first

Deferred records must include:
- `deferred_reason`
- `recheck_at`
- `max_rechecks`
- `dependency_watchlist`

## Retry Policy

Retries are only permitted when `retryable = true`.

A retry policy must define:
- max retry count
- backoff strategy
- retry trigger source
- whether payload may be mutated before retry
- whether the retry keeps the same idempotency key

Suggested retry classes:
- `none`
- `manual_only`
- `bounded_exponential`
- `dependency_triggered`
- `time_window_recheck`

## Dependency Rules

Dependencies may be:
- hard dependencies — approval blocked until satisfied
- soft dependencies — warning only
- ordering dependencies — must occur before this signal
- consistency dependencies — related records must align

All hard dependencies must be explicitly enumerated or inferable from the active manifest.

## Audit Requirements

Each lifecycle event must append an audit record containing:
- signal id
- prior state
- new state
- actor/system layer
- reason code if applicable
- timestamp
- tenant id
- correlation id

## Consumption Rule

Domain services may only consume signals in `approved` state.

No domain service may bypass the lifecycle, self-approve, or mutate governance decisions outside of an authorized reversal path.
