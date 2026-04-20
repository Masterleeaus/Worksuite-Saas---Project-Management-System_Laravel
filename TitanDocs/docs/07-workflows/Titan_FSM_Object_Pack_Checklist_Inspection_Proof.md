# Titan FSM Object Pack — Checklist, Inspection, ProofOfService

Defines execution-verification objects used during and after onsite work.

## Checklist

### Purpose
Ensures required work steps are visible and trackable.

### Core Fields
- checklist_id
- tenant_id
- site_id
- service_job_id
- template_id
- completion_status
- required_for_close

## Inspection

### Purpose
Captures quality or compliance review after or during service.

### Core Fields
- inspection_id
- tenant_id
- visit_id
- inspector_id
- inspection_status
- findings_summary
- followup_required

## ProofOfService

### Purpose
Provides evidence the visit occurred and reached required completion criteria.

### Core Fields
- proof_id
- tenant_id
- visit_id
- proof_type
- media_reference
- timestamp
- geo_reference
- signature_status
- proof_status

## Required Guards

- proof completeness before completion if policy requires
- inspection can reopen completed visit if severe failure found
- checklist required items must be finished before close when enforced

## Suggested Tables

- jobs_checklists
- jobs_inspections
- jobs_proof_records
