# Titan DB Mapping Guide

Provides a naming and mapping layer from architectural objects to database families.

## Purpose

Architecture docs define concepts; this guide helps builders translate them into stable table families and predictable prefixes.

## Table Family Pattern

Use grouped families such as:
- core_*
- mesh_*
- membership_*
- package_*
- system_*
- jobs_*
- finance_*
- comms_*
- social_*
- admin_*
- nexus_*

## Mapping Examples

### Customer
Object: Customer  
Suggested family: jobs_customers

### Site
Object: Site  
Suggested family: jobs_sites

### ServiceJob
Object: ServiceJob  
Suggested family: jobs_service_jobs

### Visit
Object: Visit  
Suggested family: jobs_visits

### Invoice
Object: Invoice  
Suggested family: finance_invoices

### Payment
Object: Payment  
Suggested family: finance_payments

### Thread
Object: Thread  
Suggested family: comms_threads

### Draft
Object: Draft  
Suggested family: social_drafts

## General Rules

- prefer stable object nouns
- preserve tenant boundary fields
- include origin tags for imported data
- keep audit and status fields explicit
- avoid mixing unrelated domains into one table

## Common Support Columns

- id
- tenant_id
- origin
- status
- created_at
- updated_at
- deleted_at
- approved_at
- source_reference
