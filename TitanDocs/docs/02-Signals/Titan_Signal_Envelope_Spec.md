# Titan Signal Envelope Specification

Defines the canonical structure for all signals traveling through Titan Zero.

## Core Fields
- signal_id
- tenant_id
- origin
- intent
- scope
- dependencies
- priority
- expiry
- created_at

## Validation Fields
- schema_status
- logic_status
- idempotency_status

## Governance Fields
- permission_status
- compliance_status
- quota_status
- cross_domain_status

## Approval Fields
- readiness_status
- dependency_resolution
- sentinel_status

## Execution Fields
- execution_target
- execution_window
- retry_policy

Signals move through states:
process → processing → processed → approved
