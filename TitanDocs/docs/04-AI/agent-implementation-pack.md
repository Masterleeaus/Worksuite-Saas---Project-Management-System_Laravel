# Titan Agent Implementation Pack

Defines the minimum bounded pack an implementation agent should receive when building or modifying one Titan area.

## Purpose

Agents perform better when given a bounded, canonical pack instead of an unbounded repository or full schema dump.

## Required Sections

1. Purpose
2. Domain
3. Canonical objects
4. State registry
5. Tool registry
6. Approval and policy rules
7. Tenant fence rules
8. Signal examples
9. Manifest/slice references
10. Open risks

## Recommended Inputs

- one slice manifest
- one or more object packs
- relevant enum registry excerpt
- relevant reason code excerpt
- channel/tool examples if applicable
- no unrelated domains unless handoff-required

## Example Pack Targets

### Jobs agent
Needs:
- Jobs mode spec
- ServiceJob/Visit pack
- Checklist/Inspection/Proof pack
- approval rules
- DB mapping excerpt

### Finance agent
Needs:
- Finance mode spec
- Quote/Invoice/Payment pack
- consent rules for reminders
- approval matrix
- channel adapter examples

### Comms agent
Needs:
- Comms mode spec
- consent model
- delivery state model
- channel adapter examples
- review queue examples

## Anti-Patterns

Avoid giving agents:
- full repo dump without bounded spec
- deprecated manifests mixed with current rules
- raw DB structure without domain map
- unrelated mode packs
