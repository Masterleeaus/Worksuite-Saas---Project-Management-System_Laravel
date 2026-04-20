# Titan Tool Execution Contract

Defines how Titan Zero proposes, validates, approves, and invokes tools without collapsing governance, tenant safety, or auditability.

## Purpose

Tools are not called directly from raw model output.  
All tool actions must pass through signal, governance, and approval layers unless explicitly classified as safe-read operations.

## Tool Classes

### 1. Read Tools
Used to inspect state without mutating system data.

Examples:
- search
- read record
- list inbox threads
- fetch schedule
- inspect manifest
- telemetry query

### 2. Write Tools
Used to create, update, delete, send, schedule, assign, or execute downstream side effects.

Examples:
- create quote
- assign visit
- send invoice
- send message
- schedule reminder
- update job state

### 3. External Action Tools
Used to invoke systems outside Titan-managed state.

Examples:
- third-party API call
- webhook dispatch
- payment request
- channel send
- calendar write
- SMS delivery
- voice call initiation

### 4. High-Risk Tools
Used for financially, legally, or operationally sensitive actions.

Examples:
- issue refund
- cancel service agreement
- delete records
- send bulk messages
- modify package or permissions
- close invoice or payroll state

## Required Tool Metadata

Every tool declaration must define:

- tool_key
- display_name
- class
- domain
- input_schema
- output_schema
- tenant_scope
- permission_scope
- idempotency_mode
- side_effect_level
- approval_requirement
- retry_mode
- timeout_policy
- audit_level
- rollback_strategy

## Invocation Flow

1. User or automation creates intent
2. Intent becomes signal
3. SignalAI validates shape, scope, schema, idempotency
4. AEGIS validates policy, permissions, quota, tenant fence
5. Sentinel verifies readiness and dependencies
6. Processing queue holds pending action if approval required
7. Tool execution adapter invokes tool
8. Output normalized into result envelope
9. Audit log records input, output, status, correlation ids
10. Failure or success emits downstream signals

## Safety Levels

### Safe Read
May execute immediately if:
- tenant scope is clear
- no side effects
- permission check passes
- no hidden escalation

### Approved Write
May execute after system approval if:
- policy allows automation
- user calibration threshold is met
- tool is not marked high-risk

### Human-Required
Must enter review queue if:
- financial action
- customer-facing send
- destructive operation
- compliance-sensitive change
- confidence below threshold

## Idempotency Rules

All write and external tools must define one of:

- strict_key
- natural_key
- once_per_window
- no_retry_allowed

Idempotency must bind to tenant + object + action + execution window.

## Output Normalization

Tool outputs must be normalized into:

- success
- partial_success
- deferred
- failed
- rejected

And include:

- result_code
- summary
- changed_objects
- emitted_signals
- user_visible_message
- retry_hint
- audit_pointer

## Forbidden Direct Patterns

The following patterns are invalid:

- raw model calls tool without policy check
- unscoped cross-tenant reads
- write action without audit log
- send action without channel adapter response mapping
- external action without timeout and retry mode
- destructive action without rollback or tombstone policy

## Audit Requirements

Each invocation must log:

- who/what initiated
- reasoning source
- approval source
- exact tool version
- input hash
- output hash
- start and end times
- downstream signals
- failure code if any

## Recommended Tables

- system_tool_registry
- system_tool_permissions
- system_tool_invocations
- system_tool_results
- system_tool_failures
