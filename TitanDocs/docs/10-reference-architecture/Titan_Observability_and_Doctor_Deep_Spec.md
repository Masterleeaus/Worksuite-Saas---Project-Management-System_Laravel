# Titan Observability and Doctor Deep Specification

Defines deep health, telemetry, drift detection, and operator diagnostics.

## Purpose

Doctor is not just uptime monitoring.  
It is the operational inspection layer for signals, workflows, tools, manifests, channels, sync, and approvals.

## Core Diagnostic Domains

- signals
- workflows
- tools
- channels
- sync
- permissions
- approvals
- manifests
- packages
- storage
- queues

## Health Check Classes

- alive_check
- dependency_check
- drift_check
- latency_check
- policy_check
- integrity_check
- backlog_check

## Example Doctor Questions

- Are delivery events mapping to canonical states?
- Are signals stalling in processing?
- Are review items expiring unhandled?
- Are package manifests mismatched with installed state?
- Are node sync conflicts increasing?
- Are any tools failing above threshold?

## Required Metrics

- signal throughput
- approval latency
- queue depth
- workflow failure rate
- channel failure rate
- sync conflict count
- manifest drift count
- retry volume
- compensation volume

## Suggested Tables

- admin_health_checks
- system_telemetry_metrics
- system_drift_events
- system_backlog_snapshots
- system_latency_samples
