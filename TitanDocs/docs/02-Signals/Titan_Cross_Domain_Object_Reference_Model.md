# Titan Cross-Domain Object Reference Model

Defines how one domain safely references objects owned by another domain.

## Purpose

Titan domains must collaborate without collapsing boundaries or duplicating source-of-truth objects.

## Reference Rules

- references must be explicit
- source domain remains canonical owner
- target domain may cache labels, not authority state
- tenant match required unless explicit bridge exists

## Example References

- Jobs references Customer from jobs_customers
- Finance references Site for invoice context
- Comms references Invoice for reminder thread
- Social references Campaign-linked customer segment, not finance balances directly

## Required Fields

- ref_id
- tenant_id
- source_domain
- source_object_type
- source_object_id
- target_domain
- target_use
- trust_state

## Suggested Tables

- system_object_refs
- system_cross_domain_links
- system_ref_audit
