# Titan Mode Handoff and Cross-Mode Triggers

Defines how one operational mode passes context, intent, and bounded actions to another mode.

## Purpose

Modes must remain distinct, but real work crosses boundaries.  
This spec keeps handoffs explicit rather than collapsing all logic into a single mixed system.

## Example Handoffs

### Jobs → Finance
Completed visit creates invoice candidate.

### Finance → Comms
Overdue invoice creates reminder draft.

### Comms → Jobs
Customer requests reschedule from inbox thread.

### Admin → All Modes
Policy update changes approval thresholds.

### Social → Comms
Social response escalates into customer support thread.

## Handoff Record

- handoff_id
- source_mode
- target_mode
- tenant_id
- trigger_signal
- object_refs
- summary
- allowed_actions
- approval_state
- created_at

## Required Rules

- handoff must preserve tenant context
- handoff must use bounded object refs
- handoff must not leak full-mode internals unnecessarily
- target mode must re-check permissions and policy
- user-visible actions must remain auditable

## Suggested Tables

- system_mode_handoffs
- system_cross_mode_triggers
- system_handoff_audit
