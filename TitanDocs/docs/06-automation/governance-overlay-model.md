# Titan Governance Overlay Model

Defines how governance rules layer over signals, tools, workflows, modes, tenants, and channels.

## Purpose

Governance overlays let Titan apply policy without rewriting every domain spec.

## Overlay Types

- tenant overlay
- mode overlay
- tool overlay
- channel overlay
- finance overlay
- compliance overlay
- temporary incident overlay

## Inputs

- tenant_id
- mode
- action_type
- object_type
- channel
- risk_level
- confidence
- time_window

## Outputs

- allowed
- review_required
- blocked
- limited_scope
- fallback_required

## Example Uses

- stricter finance review for one tenant
- temporary quiet-hours override for urgent service updates
- restricted outbound voice for low-trust calibration stage
- incident policy blocking bulk sends

## Suggested Tables

- system_governance_overlays
- system_overlay_rules
- system_overlay_events
