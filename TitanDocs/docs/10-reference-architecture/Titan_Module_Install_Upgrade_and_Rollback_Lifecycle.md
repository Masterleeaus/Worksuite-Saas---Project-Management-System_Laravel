# Titan Module Install, Upgrade, and Rollback Lifecycle

Defines the canonical lifecycle for introducing, upgrading, validating, and rolling back modules in tenant-safe environments.

## Purpose

Modules must install predictably, upgrade idempotently, and roll back safely without causing tenant drift, route breakage, permission holes, or schema corruption.

## Lifecycle Phases

- discovery
- compatibility validation
- install planning
- schema application
- registry activation
- permission and settings seeding
- health verification
- route/view/cache refresh
- availability publication

For upgrades:
- version comparison
- migration planning
- compatibility gate
- data/backfill tasks
- feature flag transition
- post-upgrade verification

For rollback:
- rollback eligibility check
- reverse migration or compensating patch
- registry state restore
- cache and contract refresh
- health verification

## Module Package Requirements

Every module package should define at minimum:
- identity metadata
- version
- provider(s)
- route contract
- settings contract
- permission contract
- manifest/schema reference
- health checks
- install/upgrade compatibility notes

## Compatibility Validation

Before install or upgrade, validate:
- host platform version
- required extension APIs
- required tables and contracts
- route namespace compatibility
- PHP/runtime compatibility
- front-end asset compatibility
- tenant model compatibility
- active conflicting modules

## Install Order

Recommended order:
1. validate package identity and signatures
2. check compatibility rules
3. register the module as pending
4. create or verify schema objects
5. seed permissions
6. seed settings defaults
7. register menus and navigation surfaces
8. warm views/routes/translations
9. run health checks
10. mark installed and available

## Registry States

Suggested registry states:
- `discovered`
- `validated`
- `pending_install`
- `installed`
- `enabled`
- `degraded`
- `upgrade_pending`
- `rollback_pending`
- `disabled`
- `failed`

## Upgrade Rules

Upgrades must be:
- idempotent
- version-aware
- forward-compatible when possible
- tenant-safe
- reversible when feasible

An upgrade should declare:
- `from_version`
- `to_version`
- required preconditions
- schema changes
- data migrations or backfills
- deprecations
- manual intervention requirements

## Data Migration Strategy

When schema changes are not enough, define:
- migration stage
- backfill stage
- validation stage
- cutover stage
- cleanup stage

Never combine destructive schema changes with unverified data transformations in a single opaque step.

## Rollback Eligibility

Not every upgrade is safely reversible.

Each upgrade must declare whether rollback is:
- fully reversible
- partially reversible with data loss risk
- not reversible without restore/import

Rollback must be blocked if:
- irreversible schema/data change has already cut over
- cross-module dependencies would break
- active tenant data would become orphaned

## Health Verification

Minimum checks after install/upgrade/rollback:
- provider loads
- routes resolve
- views render
- permissions seed correctly
- tenant scoping works
- settings defaults exist
- no missing dependencies remain
- health endpoint passes

## Failure Handling

If a phase fails:
- record exact phase and reason
- keep registry state truthful
- prevent partial publish as enabled
- offer compensating rollback if safe
- expose degraded/failure state to Doctor and observability

## Tenant Safety

Module operations must never:
- leak data across tenants
- assume global settings where tenant settings are required
- mark a module enabled for tenants lacking seeded settings/permissions
- silently mutate existing tenant workflows without versioned policy

## Publication Rule

A module is not considered active merely because files exist.

A module becomes active only when:
- registry says enabled
- compatibility is satisfied
- schema is valid
- permissions and settings are seeded
- health checks pass
