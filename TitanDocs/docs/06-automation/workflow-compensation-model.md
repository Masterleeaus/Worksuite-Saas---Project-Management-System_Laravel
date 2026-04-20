# Titan Workflow Compensation Model

Defines how workflows recover from failed or partially completed state transitions without corrupting business state.

## Purpose

Workflows cannot assume every transition succeeds.  
Compensation provides bounded, auditable undo or follow-up behavior for partially applied operations.

## Compensation Types

- state_revert
- followup_task_create
- alternate_channel_retry
- reopen_object
- supervisor_review
- tombstone_and_block
- partial_finalize_with_flag

## When Compensation Is Required

- customer-facing message sent but state write failed
- state write succeeded but external notification failed
- payment recovery action duplicated risk
- worker assignment changed after dispatch emitted
- approval expired mid-flow
- downstream dependency unavailable

## Compensation Record

- compensation_id
- tenant_id
- workflow_key
- root_signal_id
- failed_step_key
- compensation_type
- target_object_type
- target_object_id
- operator_action_required
- status
- created_at
- resolved_at

## Required Rules

- compensation must not bypass approval policy
- compensation must be idempotent
- compensation must reference failure source
- compensation should preserve audit chain
- destructive compensation requires review when irreversible

## Suggested Tables

- system_compensations
- system_workflow_failures
- system_recovery_jobs
