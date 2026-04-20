# Titan Permission and Tenant Fence Model

Defines identity scope, role authority, company boundaries, and safe access rules across Titan domains.

## Purpose

Tenant safety is mandatory.  
Every signal, tool, memory record, workflow action, and channel message must resolve to the correct tenant boundary.

## Core Tenant Rule

Company scope is the canonical tenant boundary for new work.  
Legacy compatibility fields may exist, but new policy resolution must normalize to canonical tenant identity.

## Permission Layers

### 1. Authentication
Who is the actor?

### 2. Role Authority
What general powers do they have?

### 3. Object Access
Can they touch this specific record?

### 4. Domain Policy
Is this action allowed in this module or mode?

### 5. Automation Policy
May Titan do this for them automatically?

## Actor Types

- owner
- admin
- manager
- dispatcher
- field_worker
- finance_user
- marketer
- customer
- system_ai
- integration_adapter

## Required Access Inputs

- actor_id
- actor_type
- tenant_id
- role_set
- object_tenant_id
- object_visibility
- requested_action
- domain
- channel

## Fence Rules

Actions must be rejected if:
- actor tenant does not match object tenant
- cross-tenant object link is unresolved
- imported data lacks origin tagging
- delegated action exceeds role scope
- automation attempts hidden privilege escalation

## Special Cases

### Shared Objects
Some objects may be cross-scope but must use explicit bridge rules.

### Imported Data
Imported records must preserve origin tags and trust state.

### External Channels
Outgoing messages must resolve both tenant ownership and allowed sending identity.

## Recommended Tables

- system_roles
- system_permissions
- system_object_access
- system_tenant_links
- system_origin_tags
- system_access_audit
