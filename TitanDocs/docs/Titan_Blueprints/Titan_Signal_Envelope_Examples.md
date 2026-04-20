# Titan Signal Envelope Examples

Provides concrete signal examples for builders and agents.

## Example 1 — Schedule a Visit

```json
{
  "signal_id": "sig_001",
  "tenant_id": "cmp_100",
  "origin": "jobs_mode",
  "intent": "schedule_visit",
  "scope": {
    "object_type": "Visit",
    "object_id": "vis_501"
  },
  "dependencies": ["site_available", "worker_available"],
  "priority": "normal",
  "schema_status": "valid",
  "logic_status": "valid",
  "idempotency_status": "pending",
  "permission_status": "pending",
  "compliance_status": "pending",
  "readiness_status": "pending"
}
```

## Example 2 — Send Invoice Reminder

```json
{
  "signal_id": "sig_002",
  "tenant_id": "cmp_100",
  "origin": "finance_mode",
  "intent": "send_invoice_reminder",
  "scope": {
    "object_type": "Invoice",
    "object_id": "inv_990"
  },
  "dependencies": ["consent_valid", "invoice_overdue"],
  "priority": "high",
  "execution_target": "channel_adapter",
  "approval_requirement": "review_required"
}
```

## Example 3 — Publish Social Draft

```json
{
  "signal_id": "sig_003",
  "tenant_id": "cmp_100",
  "origin": "social_mode",
  "intent": "publish_draft",
  "scope": {
    "object_type": "Draft",
    "object_id": "drf_210"
  },
  "dependencies": ["asset_ready", "approval_complete", "channel_available"],
  "priority": "scheduled",
  "execution_window": "2026-04-21T09:00:00Z"
}
```
