# Titan FSM Object Pack — Customer and Site

Defines canonical fields, relationships, and behavioral rules for Customer and Site.

## Customer

### Purpose
Represents the business relationship endpoint.

### Core Fields
- customer_id
- tenant_id
- display_name
- status
- primary_contact_method
- billing_profile_id
- consent_profile_id
- memory_scope_id

### Key Relationships
- one-to-many Sites
- one-to-many Quotes
- one-to-many Invoices
- one-to-many Threads

### Behavioral Rules
- customer state must remain tenant-scoped
- outbound communication must check consent
- account flags may affect scheduling and finance actions

## Site

### Purpose
Represents the service location where work occurs.

### Core Fields
- site_id
- tenant_id
- customer_id
- site_name
- address
- access_profile_id
- service_window
- active_status
- hazard_profile
- site_memory_scope_id

### Key Relationships
- belongs to Customer
- one-to-many Visits
- one-to-many Checklists
- one-to-many Inspections
- one-to-many Proof records

### Site Memory Examples
- access code
- parking notes
- alarm instructions
- pet warning
- preferred entry route

## Suggested Tables

- jobs_customers
- jobs_sites
- system_memory_records
- comms_customer_consents
