# Titan Approval Policy Matrix

Defines when Titan may act automatically, when it must ask, and how approval thresholds evolve over time.

## Purpose

Approval is a policy layer, not an afterthought.  
The platform must distinguish between what Titan can propose, what Titan can prepare, and what Titan may execute.

## Core Approval States

- propose_only
- draft_only
- review_required
- auto_execute_allowed
- blocked

## Approval Dimensions

Policy may vary by:

- tenant
- user role
- domain
- tool class
- channel
- risk level
- confidence level
- customer impact
- financial impact

## Typical Matrix

### Low Risk
Examples:
- read schedule
- draft internal note
- suggest checklist item

Default:
- auto allowed for reads
- propose or draft for writes

### Medium Risk
Examples:
- schedule visit
- assign worker
- send reminder
- update workflow state

Default:
- review required early in calibration
- auto allowed after repeated approvals

### High Risk
Examples:
- invoice send
- refund
- contract cancellation
- payroll change
- bulk outreach
- delete records

Default:
- human approval required
- optional dual approval for some tenants

## Calibration Model

Early-stage assistants operate in strict mode:

- 100 percent review for writes
- approvals become training signals
- denials become correction deltas

As evidence increases, policy may loosen by:
- domain
- action type
- user
- confidence band

## Inputs to Approval Decision

- signal validity
- policy rule
- user permissions
- tenant automation level
- historical approval rate
- current confidence
- object criticality
- time sensitivity
- communication sensitivity

## Approval Outputs

Every evaluation should produce:

- approval_state
- reason_code
- human_review_required
- approval_scope
- expiry
- escalation_target

## Required Reasons

Examples:
- financial_high_risk
- customer_message_requires_review
- confidence_below_threshold
- calibration_mode_active
- policy_blocks_automation
- tenant_opt_out
- destructive_action

## Review Queue Integration

When approval is needed, the action should enter processing queue with:

- short summary
- affected records
- reversible or not
- risk tags
- approve/edit/deny options
- correction capture

## Recommended Tables

- system_approval_policies
- system_approval_rules
- system_approval_events
- system_approval_thresholds
- system_review_queue
