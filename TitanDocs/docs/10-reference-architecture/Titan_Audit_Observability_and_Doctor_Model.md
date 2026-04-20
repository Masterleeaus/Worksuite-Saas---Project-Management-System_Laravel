# Titan Audit, Observability, and Doctor Model

Defines how Titan records system behavior, surfaces health, and exposes actionable diagnostics to operators and agents.

## Purpose

A privacy-first operational system still needs rich visibility. Observability should expose state, failures, drift, and health without turning runtime behavior into an opaque black box.

## Core Layers

- audit log
- event telemetry
- health checks
- diagnostics / Doctor
- alerting
- reconciliation and drift detection

## Audit vs Telemetry

### Audit
Immutable or high-trust records of meaningful state transitions and approvals.

Examples:
- signal approved
- policy denied
- invoice posted
- user approved automation
- message sent or suppressed

### Telemetry
Operational traces and measurements used for health and performance.

Examples:
- queue depth
- retry counts
- sync lag
- API latency
- rendering failure rate

## Doctor Responsibilities

Doctor should surface:
- missing providers or routes
- manifest/schema mismatches
- module install drift
- stale caches
- missing permissions/settings seeds
- failed health checks
- queue congestion
- channel credential/config issues
- sync backlog and conflict spikes

## Health Check Classes

Suggested classes:
- boot health
- module health
- database health
- route/view health
- channel health
- queue/worker health
- sync health
- AI tool health
- storage/attachment health

## Severity Levels

- info
- warning
- degraded
- critical
- fatal

## Diagnostic Record Shape

Each issue should include:
- component
- severity
- first_seen_at
- last_seen_at
- detection_source
- probable_cause
- recommended_fix
- auto_fixable boolean
- linked evidence

## Drift Detection

Doctor should detect drift between:
- registry vs actual files
- manifest vs database schema
- routes expected vs routes loaded
- permissions expected vs permissions seeded
- tenant module state vs package settings

## Operator Surfaces

Recommended surfaces:
- command-centre summary cards
- per-module doctor pages
- per-channel diagnostics
- sync/conflict dashboard
- approval/governance event streams

## Non-Obscurity Rule

Critical automation should always leave a diagnosable trail.

If the system took an action, delayed an action, or rejected an action, an operator or agent should be able to determine why.
