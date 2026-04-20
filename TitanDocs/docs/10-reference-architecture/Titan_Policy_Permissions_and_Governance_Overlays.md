# Titan Policy, Permissions, and Governance Overlays

Defines how Titan applies tenant rules, role permissions, limits, and safety overlays before any approval-ready action can exist.

## Layers
1. Identity layer
2. Tenant layer
3. Role layer
4. Package/subscription layer
5. Domain policy layer
6. Risk overlay
7. Manual review overlay

Each layer can:
- allow
- constrain
- require review
- deny

## Policy Evaluation Order
- verify actor identity
- verify tenant scope
- verify object ownership or access rights
- verify package entitlement
- verify domain-specific policy
- verify quotas and spending limits
- verify risk conditions
- produce consolidated result

## Core Concepts
### Permission
A direct grant to view, create, modify, approve, or administer an object or action.

### Policy
A rule that adds conditions to permission. Example:
"Dispatch may be edited by scheduler role only before crew sign-in."

### Overlay
A temporary or context-sensitive rule that modifies normal policy. Examples:
- out-of-hours restrictions
- approval needed above quote threshold
- communications freeze during incident mode

## Protected Domains
Higher scrutiny applies to:
- money movement
- payroll
- contract changes
- customer communications at scale
- data export
- deletion
- cross-tenant operations

## Policy Result Object
- policy_result_id
- actor_id
- tenant_id
- action_type
- target_type
- allow_status
- review_required
- denial_codes[]
- applied_rules[]
- expiry_at

## Common Denial Codes
- actor_unverified
- tenant_scope_missing
- permission_missing
- package_limit_exceeded
- protected_action_requires_review
- policy_time_window_violation
- risk_threshold_exceeded

## Review Escalation
Policies may route to:
- user review
- manager review
- finance review
- admin review
- governance-only hold

## Audit Requirements
Every policy pass should record:
- rules evaluated
- inputs used
- overrides applied
- final outcome
- human approver if present

## Governance Principle
Permissions alone are never sufficient for protected actions.
