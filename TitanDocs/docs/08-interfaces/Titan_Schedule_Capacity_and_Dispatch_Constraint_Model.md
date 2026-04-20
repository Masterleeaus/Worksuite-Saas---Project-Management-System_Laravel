# Titan Schedule, Capacity, and Dispatch Constraint Model

Defines the constraint model used by scheduling, dispatching, and rescheduling engines.

## Goals
- avoid overbooking
- preserve travel realism
- respect skills and permissions
- protect labor and rest constraints
- support recurring work and urgent exceptions

## Core Scheduling Objects
- schedule_window
- crew
- technician
- visit
- route_run
- dispatch_assignment
- blackout_rule
- availability_rule
- skill_requirement
- travel_estimate

## Constraint Families
### Hard Constraints
Must never be violated automatically:
- tenant boundary
- worker unavailable
- overlapping assignment
- required skill missing
- locked appointment
- legal rest minimum
- site access window violated

### Soft Constraints
May be traded off with scoring:
- preferred technician
- route efficiency
- customer preference
- continuity with previous visit
- overtime avoidance
- workload balance

## Capacity Model
Capacity should consider:
- shift duration
- unpaid break windows
- travel time
- setup/packdown buffer
- checklist complexity
- inspection time
- expected issue probability

## Dispatch Scoring Dimensions
- feasibility
- travel cost
- continuity
- urgency
- revenue protection
- SLA risk
- worker fairness

## Rescheduling Triggers
- worker absence
- customer request
- traffic/travel delay
- earlier task overrun
- missing access
- weather or emergency event

## Locked vs Flexible States
### Locked
Manual review required to move.
### Flexible
Can be auto-adjusted within permitted limits.

## Constraint Output
Every scheduling proposal should emit:
- feasible yes/no
- violated_hard_constraints[]
- soft_penalty_breakdown[]
- recommended_assignments[]
- manual_review_required yes/no

## Approval Note
High-impact schedule changes should produce proposals first, not direct execution, unless automation tier and policy allow.
