# Titan Jobs Mode Specification

Defines the operational mode for planning, scheduling, dispatching, executing, reviewing, and closing service work.

## Purpose

Jobs Mode is the execution surface for field operations.  
It should expose only actionable work-state signals, not raw data sprawl.

## Core Objects

- Customer
- Site
- ServiceAgreement
- ServiceJob
- Visit
- Checklist
- Inspection
- WorkOrder
- DispatchAssignment
- RouteRun
- ProofOfService

## Primary Lifecycle

Draft → Planned → Scheduled → Dispatched → En Route → On Site → In Progress → Awaiting Review → Completed → Invoiced → Closed

## Core Surfaces

- jobs board
- dispatch board
- day/week schedule
- unassigned queue
- route run view
- visit detail
- site memory panel
- proof/review panel

## Key Signals

- visit_created
- visit_scheduled
- assignment_proposed
- assignment_confirmed
- worker_en_route
- worker_on_site
- proof_uploaded
- review_required
- visit_completed
- visit_exception_opened

## Constraints

- tenant-scoped jobs only
- worker availability respected
- overtime rules honored
- site access memory surfaced
- checklist requirements enforced before completion
- review gate before invoice if policy requires

## Suggested Actions

- assign worker
- reschedule
- split job
- add checklist
- request approval
- notify customer
- create issue follow-up

## Required Guards

- conflict detection
- double-booking prevention
- permission checks
- proof completeness rules
- approval matrix for sensitive transitions

## Recommended Tables

- jobs_customers
- jobs_sites
- jobs_service_jobs
- jobs_visits
- jobs_checklists
- jobs_inspections
- jobs_assignments
- jobs_route_runs
- jobs_proof_records
