# Titan Runtime Envelope Model

Defines the bounded live context package supplied to reasoning, decision, and action layers for one task.

## Purpose

A runtime envelope prevents over-scanning and keeps active decisions grounded in the minimum required operational context.

## Envelope Sections

- envelope_id
- tenant_id
- mode
- initiating_signal
- object_refs
- current_states
- policy_refs
- approval_context
- memory_refs
- tool_refs
- exclusions
- expiry

## Rules

- must be tenant-scoped
- must use bounded object references
- must exclude unrelated domain data
- must preserve source references for included context
- must expire after decision window closes

## Typical Uses

- one visit assignment decision
- one overdue invoice follow-up decision
- one inbound message reply suggestion
- one social draft approval decision

## Suggested Stores

- system_runtime_envelopes
- system_envelope_refs
- system_envelope_expiry
