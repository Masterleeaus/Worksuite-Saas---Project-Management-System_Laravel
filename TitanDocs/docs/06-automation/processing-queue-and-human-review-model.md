# Titan Processing Queue and Human Review Model

Defines the queue where Titan stores proposals, pending actions, review requests, and deferred work.

## Purpose
Titan proposes actions before execution. The processing queue is the human-visible review layer and the training surface for refinement.

## Queue Item Types
- action_proposal
- approval_request
- evidence_request
- conflict_review
- policy_exception
- schedule_change
- communication_draft
- finance_alert

## Core Fields
- queue_item_id
- tenant_id
- domain
- item_type
- source_signal_ids[]
- related_object_refs[]
- priority
- risk_score
- confidence_score
- review_status
- proposed_by
- created_at
- due_at

## Review States
- queued
- in_review
- approved
- approved_with_edits
- denied
- expired
- superseded

## Human Actions
A reviewer may:
- approve
- deny
- edit then approve
- request more evidence
- reassign
- defer
- convert to template/rule

## Learning Value
Review outcomes should become refinement signals:
- approval pattern
- edited fields
- denial reasons
- required evidence deltas
- risk threshold adjustments

## Protected Principle
Until explicitly approved, queue items do not execute in protected domains unless policy explicitly permits autonomous action.

## Aging Rules
Queue items may:
- escalate after SLA threshold
- expire if context is stale
- refresh if newer evidence arrives
- collapse duplicates into a single review object

## UI Recommendations
Review cards should show:
- what is proposed
- why it was proposed
- what evidence supports it
- what risks remain
- what will happen if approved
