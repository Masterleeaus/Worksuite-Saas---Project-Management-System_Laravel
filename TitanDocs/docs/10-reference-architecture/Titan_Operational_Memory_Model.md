# Titan Operational Memory Model

Defines how Titan stores scoped operational memory for users, teams, customers, sites, jobs, and channels.

## Purpose

Operational memory is not generic chat memory.  
It stores reusable business context that improves future actions without forcing users to repeat operational details.

## Memory Layers

### 1. User Memory
Personal working preferences for one authenticated user.

Examples:
- preferred wording
- preferred dashboard mode
- reminder style
- action approval habits

### 2. Company Memory
Tenant-wide memory shared across the business.

Examples:
- company rules
- default job flow
- invoice follow-up policy
- preferred channels

### 3. Site Memory
Location-specific reusable context.

Examples:
- access code
- lockbox location
- gate instructions
- parking notes
- safety requirements
- site-specific checklist notes

### 4. Customer Memory
Relationship and communication context.

Examples:
- preferred contact window
- escalation path
- invoice delivery preference
- recurring concerns

### 5. Job Memory
Memory attached to service patterns, recurring work, or job families.

Examples:
- recurring issue notes
- preferred equipment
- before/after proof expectations
- exception history

### 6. Channel Memory
Channel-scoped delivery context.

Examples:
- WhatsApp allowed
- SMS backup only
- email preferred for invoices
- voice disabled after hours

## Memory Shape

Each memory record should define:

- memory_id
- tenant_id
- memory_scope
- object_type
- object_id
- memory_key
- memory_value
- confidence
- source_type
- source_reference
- privacy_level
- last_verified_at
- expires_at
- status

## Memory Categories

- preference
- instruction
- environment
- relationship
- compliance
- exception
- learned_pattern
- user_correction
- site_access
- operational_hint

## Source Types

Memory may originate from:

- explicit user instruction
- approved action correction
- repeated confirmed behavior
- imported structured data
- reviewed field note
- site/job history
- admin policy

## Verification Levels

### Explicit
User directly stated the value.

### Observed
System inferred from repeated confirmed actions.

### Approved
Human validated the memory after proposal.

### Expired
Memory exists but must not drive automation until revalidated.

## Memory Use Rules

Operational memory may influence:

- proposed actions
- ranking
- defaults
- reminders
- routing
- communication timing
- checklist suggestions

Operational memory must not silently bypass:
- approvals
- permissions
- financial controls
- compliance rules

## Site Memory Priority

For service businesses, site memory is high-value and should support:

- access instructions
- onsite hazards
- pet notes
- alarm procedure
- consumable location
- proof requirements
- customer onsite preferences
- repeat issue history

## Privacy Rules

Memory must remain tenant-scoped by default.  
Sensitive memories require explicit privacy level labels such as:

- private_user
- tenant_shared
- restricted_ops
- compliance_locked

## Decay and Review

Memory should decay when:
- unused for long periods
- contradicted by new approved actions
- location or customer changes
- policy marks it stale

## Recommended Tables

- system_memory_records
- system_memory_links
- system_memory_reviews
- system_memory_sources
- system_memory_decay_log
