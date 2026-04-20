# Titan Builder Checklist

Defines a concise implementation checklist for developers building Titan-aligned modules, tools, or workflows.

## Core Checklist

- canonical object identified
- tenant boundary defined
- signal inputs defined
- approval requirement defined
- tool class declared
- idempotency mode declared
- reason codes mapped
- canonical statuses used
- observability hooks added
- rollback or compensation considered

## Domain Checklist

- mode handoffs defined if needed
- channel consent checked for messages
- financial actions gated for review
- site memory surfaced for onsite work
- package and rollout impact considered
- Doctor checks added for new moving parts

## Delivery Checklist

- examples included
- tables mapped
- permissions documented
- failure states documented
- no deprecated naming mixed into canonical layer
