# Titan Install and Rollback Examples

Provides concrete examples for module/package installation and rollback handling.

## Example 1 — Safe Module Enable

```json
{
  "rollout_id": "rol_2001",
  "module_key": "jobs.dispatch",
  "tenant_scope": "single_tenant",
  "stage": "enabled",
  "checks": [
    "manifest_valid",
    "permissions_seeded",
    "health_checks_passed"
  ],
  "rollback_plan": "disable_module_and_restore_previous_settings"
}
```

## Example 2 — Rollback After Route Failure

```json
{
  "rollout_id": "rol_2002",
  "module_key": "finance.recovery",
  "tenant_scope": "tenant_group",
  "stage": "rolled_back",
  "reason_code": "route_binding_failure",
  "rollback_actions": [
    "disable_feature_flag",
    "restore_previous_manifest_version",
    "re-run doctor checks"
  ]
}
```

## Required Fields

- rollout_id
- module_key
- tenant_scope
- stage
- checks
- rollback_plan
- reason_code
- created_at
