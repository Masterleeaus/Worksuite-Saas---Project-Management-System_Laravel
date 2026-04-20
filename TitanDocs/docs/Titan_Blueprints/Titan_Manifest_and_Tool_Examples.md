# Titan Manifest and Tool Examples

Provides concrete examples of manifests, slice packs, and tool declarations.

## Example Tool Declaration

```json
{
  "tool_key": "jobs.assign_worker",
  "display_name": "Assign Worker",
  "class": "write",
  "domain": "jobs",
  "tenant_scope": "company",
  "permission_scope": "jobs.assign",
  "idempotency_mode": "strict_key",
  "side_effect_level": "medium",
  "approval_requirement": "review_required",
  "retry_mode": "safe_retry",
  "audit_level": "full"
}
```

## Example Slice Manifest

```json
{
  "slice_key": "jobs.dispatch",
  "entities": ["ServiceJob", "Visit", "DispatchAssignment", "RouteRun"],
  "signals": ["visit_scheduled", "assignment_proposed", "assignment_confirmed"],
  "tools": ["jobs.assign_worker", "jobs.reschedule_visit"],
  "policies": ["availability_check", "overtime_policy", "tenant_fence"]
}
```

## Example Knowledge Pack Header

```yaml
pack_key: finance.recovery
version: 1
purpose: overdue invoice follow-up
included_sources:
  - finance invoices
  - customer consent
  - follow-up policy
  - channel adapter status
exclusions:
  - payroll
  - tax filing
```
