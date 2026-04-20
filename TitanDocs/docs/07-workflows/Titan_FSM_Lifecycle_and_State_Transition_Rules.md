# Titan FSM Lifecycle and State Transition Rules

Defines the canonical service-business object lifecycle and how transitions should be validated across planning, dispatch, service execution, review, invoicing, and closure.

## Purpose

FSM objects must move through predictable lifecycle states so automation, dashboards, field apps, and approvals all reason over the same operational truth.

## Primary Operational Object

The core operational lifecycle is centered on service work, whether named:
- ServiceJob
- WorkOrder
- Visit
- DispatchAssignment

Different object layers may carry narrower states, but they should map back to a canonical lifecycle spine.

## Canonical Lifecycle

- Draft
- Planned
- Scheduled
- Dispatched
- En Route
- On Site
- In Progress
- Awaiting Review
- Completed
- Invoiced
- Closed
- Cancelled

## State Semantics

### Draft
Object exists but is not operationally committed.

### Planned
Scope exists and is intended to happen, but no fixed execution slot is fully committed.

### Scheduled
Time, site, and resource plan exist.

### Dispatched
A technician/crew assignment has been issued.

### En Route
Assigned worker is actively traveling or has explicitly departed.

### On Site
Arrival has been confirmed.

### In Progress
Service work has started.

### Awaiting Review
Execution ended, but QA, proof, customer signoff, or supervisor checks remain.

### Completed
Operational service is considered done.

### Invoiced
Financial document has been issued against completed work.

### Closed
The work is operationally and financially finalized.

### Cancelled
The work is intentionally terminated before closure.

## Transition Rules

Typical allowed transitions:
- Draft -> Planned
- Planned -> Scheduled
- Scheduled -> Dispatched
- Dispatched -> En Route
- En Route -> On Site
- On Site -> In Progress
- In Progress -> Awaiting Review
- Awaiting Review -> Completed
- Completed -> Invoiced
- Invoiced -> Closed

Allowed exception transitions should be explicit, such as:
- Scheduled -> Cancelled
- Dispatched -> Cancelled
- On Site -> Awaiting Review when work was blocked but evidence must be reviewed
- Awaiting Review -> In Progress when rework is required

## Validation Gates

Transitions should check:
- required site/customer exists
- required assignment exists
- checklist or proof requirements if configured
- no blocking issue or lock is active
- user/worker has permission
- time window rules and blackouts
- required approvals or prerequisites

## Financial Coupling Rules

Operational state does not automatically imply financial state.

Recommended coupling:
- invoicing should not occur before Completed unless the policy allows deposits/progress billing
- Closed should normally require invoice/payment reconciliation policy satisfaction

## Proof and Review Rules

A service may require any combination of:
- before photos
- after photos
- signatures
- checklist completion
- issue notes
- supervisor review
- customer confirmation

These should gate movement into Completed or Closed according to policy.

## Exception States

Systems may maintain side flags in addition to lifecycle state, such as:
- blocked
- late
- issue_reported
- awaiting_access
- reschedule_requested
- payment_overdue

These are overlays, not replacements for the canonical lifecycle.
