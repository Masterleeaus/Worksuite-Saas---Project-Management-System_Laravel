# Titan Workflow Template Library

Provides reusable workflow shapes for common Titan actions.

## Template 1 — Approval-Gated Action

1. intent received
2. signal validated
3. policy evaluated
4. review queued
5. approved or denied
6. tool invoked
7. result normalized
8. audit recorded

## Template 2 — Channel Send With Fallback

1. draft prepared
2. consent checked
3. channel selected
4. send attempted
5. delivery failed
6. fallback rule evaluated
7. alternate send attempted
8. thread updated

## Template 3 — Job Completion to Invoice

1. visit completed
2. proof checked
3. review gate evaluated
4. completion approved
5. invoice candidate created
6. finance review or draft issue
7. notify if allowed

## Template 4 — Offline Sync Reconcile

1. local change recorded
2. sync batch emitted
3. conflict detected
4. resolution selected
5. winner applied
6. audit recorded
7. downstream signals emitted
