# Titan Approval Record Examples

Provides concrete examples of approval outcomes for actions entering governance and review.

## Example 1 — Approved Assignment

```json
{
  "approval_id": "apr_1001",
  "tenant_id": "cmp_100",
  "action_type": "assign_worker",
  "approval_state": "approved",
  "reason_code": "policy_allows_after_calibration",
  "review_required": false,
  "approved_by": "system_ai",
  "approval_scope": "jobs.assign",
  "expires_at": "2026-04-21T10:00:00Z"
}
```

## Example 2 — Review Required for Refund

```json
{
  "approval_id": "apr_1002",
  "tenant_id": "cmp_100",
  "action_type": "issue_refund",
  "approval_state": "review_required",
  "reason_code": "financial_high_risk",
  "review_required": true,
  "approved_by": null,
  "approval_scope": "finance.refund",
  "expires_at": "2026-04-21T12:00:00Z"
}
```

## Example 3 — Blocked Outreach

```json
{
  "approval_id": "apr_1003",
  "tenant_id": "cmp_100",
  "action_type": "send_marketing_message",
  "approval_state": "blocked",
  "reason_code": "consent_missing",
  "review_required": false,
  "approved_by": null,
  "approval_scope": "comms.marketing",
  "expires_at": null
}
```

## Required Fields

- approval_id
- tenant_id
- action_type
- approval_state
- reason_code
- review_required
- approved_by
- approval_scope
- expires_at
- created_at
