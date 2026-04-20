# Titan Review Queue Examples

Provides concrete examples of approval items entering the processing queue.

## Example 1 — Invoice Reminder Awaiting Approval

```json
{
  "review_id": "rvw_1001",
  "tenant_id": "cmp_100",
  "action_type": "send_invoice_reminder",
  "risk_tags": ["customer_message", "finance"],
  "summary": "Send overdue reminder for Invoice inv_990 via SMS fallback email",
  "affected_objects": ["Invoice:inv_990", "Customer:cst_212"],
  "reversible": false,
  "approval_options": ["approve", "edit", "deny"],
  "reason_code": "customer_message_requires_review"
}
```

## Example 2 — Worker Assignment Review

```json
{
  "review_id": "rvw_1002",
  "tenant_id": "cmp_100",
  "action_type": "assign_worker",
  "risk_tags": ["schedule_change"],
  "summary": "Assign Worker wrk_77 to Visit vis_501 at 9:00 AM",
  "affected_objects": ["Visit:vis_501", "Worker:wrk_77"],
  "reversible": true,
  "approval_options": ["approve", "edit", "deny"],
  "reason_code": "calibration_mode_active"
}
```

## Required Review Fields

- review_id
- tenant_id
- action_type
- summary
- affected_objects
- risk_tags
- reversible
- approval_options
- reason_code
- expires_at
- created_at
