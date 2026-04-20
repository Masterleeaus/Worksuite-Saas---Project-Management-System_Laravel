# Titan Node Sync and Conflict Examples

Provides concrete examples for local-first sync and reconciliation.

## Example 1 — Last Writer Wins for Low-Risk Preference

```json
{
  "conflict_id": "cnf_1001",
  "object_type": "UserPreference",
  "object_id": "pref_55",
  "node_a_version": "v10",
  "node_b_version": "v11",
  "resolution": "timestamp_priority",
  "winner": "node_b"
}
```

## Example 2 — Sentinel Override for Site Access Memory

```json
{
  "conflict_id": "cnf_1002",
  "object_type": "SiteMemory",
  "object_id": "mem_700",
  "node_a_version": "v4",
  "node_b_version": "v5",
  "resolution": "sentinel_override",
  "winner": "node_a",
  "reason": "newer record lacked approval verification"
}
```

## Example 3 — Manual Review for Financial Conflict

```json
{
  "conflict_id": "cnf_1003",
  "object_type": "Payment",
  "object_id": "pay_900",
  "node_a_version": "v8",
  "node_b_version": "v8b",
  "resolution": "manual_review_required",
  "winner": null,
  "reason": "financial divergence detected"
}
```

## Required Fields

- conflict_id
- tenant_id
- object_type
- object_id
- node_versions
- resolution
- winner
- reason
- created_at
