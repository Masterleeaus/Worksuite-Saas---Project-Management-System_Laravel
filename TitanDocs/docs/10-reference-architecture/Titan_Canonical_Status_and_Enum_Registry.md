# Titan Canonical Status and Enum Registry

Defines canonical statuses and enum families used across Titan domains to reduce semantic drift.

## Purpose

Builders and agents should not invent new status labels when a canonical state already exists.

## Global State Families

### Signal States
- process
- processing
- processed
- approved
- rejected
- deferred
- expired

### Approval States
- propose_only
- draft_only
- review_required
- approved
- blocked
- denied
- expired

### Delivery States
- drafted
- queued
- handed_off
- provider_accepted
- in_transit
- delivered
- opened
- interacted
- failed
- bounced
- blocked
- expired
- cancelled

### Workflow States
- pending
- active
- waiting_dependency
- waiting_review
- compensated
- completed
- failed
- cancelled

### Sync Resolution States
- merged
- timestamp_priority
- sentinel_override
- manual_review_required
- blocked

## Jobs Domain Families

### Visit States
- draft
- planned
- scheduled
- dispatched
- en_route
- on_site
- in_progress
- awaiting_review
- completed
- closed
- cancelled

### Proof States
- missing
- partial
- ready_for_review
- accepted
- rejected

## Finance Domain Families

### Quote States
- draft
- sent
- viewed
- accepted
- rejected
- expired

### Invoice States
- draft
- issued
- delivered
- due
- overdue
- paid
- written_off
- cancelled

### Payment States
- pending
- authorized
- settled
- failed
- refunded
- reversed

## Comms Domain Families

### Thread States
- open
- pending_reply
- waiting_customer
- escalated
- closed
- archived

### Consent States
- opted_in
- opted_out
- transactional_only
- marketing_only
- service_updates_only
- unknown
- blocked

## Guidance

- use one canonical state per object context
- map provider-specific states into canonical states
- preserve original raw value separately when needed
- document deviations explicitly
