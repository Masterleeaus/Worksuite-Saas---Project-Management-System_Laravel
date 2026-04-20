# Titan Manifest Registry and Discovery Model

Defines how Titan discovers modules, tools, channels, workflows, node capabilities, and UI surfaces through manifests.

## Manifest Families
- core manifest
- slice manifest
- module manifest
- tool manifest
- workflow manifest
- channel adapter manifest
- CMS manifest
- AI surface manifest
- node capability manifest

## Registry Objectives
- avoid schema introspection when possible
- provide bounded AI scan surfaces
- support hot discovery and install
- enable capability checks before orchestration
- prevent hidden behavior

## Registry Object
Each registry entry should include:
- manifest_id
- manifest_type
- name
- version
- owner_scope
- declared_capabilities[]
- required_capabilities[]
- exposed_objects[]
- tool_endpoints[]
- ui_surfaces[]
- status
- checksum

## Discovery Rules
A component can be discovered only if:
- manifest parses successfully
- required capabilities are satisfied
- version constraints are compatible
- tenant/package policies allow exposure
- health checks pass if required

## Version Negotiation
When capabilities differ across versions:
- prefer compatible stable version
- warn on deprecated fields
- block unsafe major mismatch
- allow adapters where explicitly declared

## AI Scan Surface Rule
AI should prefer manifests plus envelopes over raw schema exploration.
Default bounded scan includes:
- core manifest
- active slice manifests
- relevant workflow/tool manifests
- current context pack references

## Failure States
- manifest_invalid
- capability_missing
- version_conflict
- checksum_mismatch
- disabled_by_policy
- healthcheck_failed

## Audit
Registry changes should be logged because capability exposure changes system behavior materially.
