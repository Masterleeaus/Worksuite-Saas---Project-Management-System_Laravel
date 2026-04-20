# Titan Error and Reason Code Registry

Defines common reason codes and error families for signals, approvals, tools, channels, sync, and workflows.

## Purpose

Reason codes allow builders, operators, and agents to understand why something was blocked, failed, deferred, or approved.

## Signal Validation Codes

- invalid_schema
- invalid_scope
- missing_required_field
- duplicate_signal
- expired_signal
- unknown_intent

## Governance Codes

- permission_denied
- tenant_fence_violation
- compliance_blocked
- quota_exceeded
- automation_blocked
- confidence_below_threshold

## Approval Codes

- calibration_mode_active
- financial_high_risk
- customer_message_requires_review
- destructive_action
- policy_allows_after_calibration
- consent_missing

## Workflow Codes

- dependency_missing
- deadlock_detected
- compensation_required
- transition_not_allowed
- review_timeout
- replay_blocked

## Tool Codes

- timeout
- external_rejection
- unsupported_capability
- idempotency_conflict
- output_normalization_failed
- rollback_unavailable

## Channel Codes

- delivery_failed
- provider_rejected
- bounced
- blocked_by_consent
- quiet_hours_block
- fallback_unavailable

## Sync Codes

- conflict_detected
- manual_review_required
- stale_version
- origin_untrusted
- sentinel_override_applied

## Usage Rules

- prefer stable snake_case codes
- pair each code with human-readable summary
- preserve provider raw code separately
- avoid duplicate semantic codes with different names
