# Titan Sentinel Approval Model

Sentinels validate domain readiness before execution.

## Approval Layers
1. Dependency validation
2. Resource availability
3. Policy compliance
4. Temporal correctness
5. Domain consistency

## Output States
- approved
- rejected
- deferred

## Rejection Codes
- missing_dependency
- policy_violation
- scheduling_conflict
- quota_exceeded
