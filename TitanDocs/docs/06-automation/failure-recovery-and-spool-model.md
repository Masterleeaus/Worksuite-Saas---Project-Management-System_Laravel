# Titan Failure Recovery and Spool Model

Defines how Titan records, replays, compensates, and recovers from failures across signals, tools, workflows, and channels.

## Purpose

Failure handling must be first-class.  
Systems that automate operations must reconstruct what happened, why it failed, and what can safely be retried.

## Recovery Layers

### 1. Signal Recovery
Handles malformed, duplicate, expired, or blocked signals.

### 2. Tool Recovery
Handles invocation failures, timeouts, partial writes, and rejected external calls.

### 3. Workflow Recovery
Handles stuck states, deadlocks, missing dependencies, and compensation.

### 4. Channel Recovery
Handles delivery failure, fallback, retry, and reroute.

### 5. Node Recovery
Handles sync conflicts, offline replay, and reconciliation.

## Failure Classes

- validation_failure
- governance_failure
- approval_failure
- timeout
- partial_write
- external_rejection
- dependency_missing
- state_deadlock
- sync_conflict
- replay_blocked

## Spool Responsibilities

Spool is the replay and reconstruction subsystem.  
It must support:

- immutable event capture
- ordered replay
- selective replay
- dry-run replay
- compensation guidance
- forensic audit reconstruction

## Replay Modes

### Full Replay
Reconstructs entire chain from root signal.

### Branch Replay
Replays one subgraph of the failure chain.

### Safe Retry
Retries only if idempotency and policy allow.

### Simulation Replay
Runs recovery logic without side effects.

## Compensation Rules

Compensation is not delete-and-forget.  
Compensation should define follow-up actions such as:

- revert state
- mark customer message failed
- queue alternate channel send
- create review item
- reopen invoice state
- restore assignment

## Recovery Metadata

Every failure record should include:

- correlation_id
- root_signal_id
- failed_stage
- failure_class
- reason_code
- retry_eligible
- replay_mode_allowed
- compensation_required
- operator_action_needed

## Human Recovery Triggers

Recovery must route to humans when:
- financial inconsistency exists
- customer may have received mixed messages
- duplicate side effects possible
- replay confidence is low
- policy forbids autonomous correction

## Recommended Tables

- system_failures
- system_recovery_jobs
- system_replay_runs
- system_compensations
- system_spool_events
- system_spool_snapshots
