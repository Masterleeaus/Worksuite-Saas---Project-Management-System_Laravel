# Titan FSM Object Pack — ServiceJob and Visit

Defines canonical fields and lifecycle rules for recurring and one-off work.

## ServiceJob

### Purpose
Represents the enduring work package or service agreement execution container.

### Core Fields
- service_job_id
- tenant_id
- customer_id
- site_id
- service_type
- recurrence_rule
- current_status
- checklist_template_id
- policy_profile_id

### Key Rules
- may generate many Visits
- may be paused without deleting history
- policy may require proof before completion

## Visit

### Purpose
Represents one dated occurrence of work.

### Core Fields
- visit_id
- service_job_id
- scheduled_start
- scheduled_end
- assigned_worker_id
- visit_status
- route_run_id
- proof_status
- review_status

### Visit Lifecycle
Draft → Planned → Scheduled → Dispatched → En Route → On Site → In Progress → Awaiting Review → Completed → Closed

### Required Guards
- worker conflict detection
- site availability rules
- proof requirements
- exception handling
- approval for sensitive reschedules

## Suggested Tables

- jobs_service_jobs
- jobs_visits
- jobs_assignments
- jobs_proof_records
