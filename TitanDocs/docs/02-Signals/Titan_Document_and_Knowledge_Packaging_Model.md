# Titan Document and Knowledge Packaging Model

Defines how docs, manifests, slices, extracted specs, and agent-readable packs should be structured.

## Purpose

Agents should not scan the full database or arbitrary file trees when a bounded manifest or knowledge slice can answer the question safely.

## Packaging Layers

### 1. Core Manifest
High-level system map.

### 2. Slice Manifest
Bounded domain-specific map for one area.

### 3. Knowledge Pack
Curated extract of schemas, rules, flows, and examples for one task.

### 4. Runtime Envelope
Minimal data package supplied to reasoning layer for a live decision.

## Packaging Goals

- bounded context
- lower hallucination risk
- easier audit
- stable handoff between agents
- smaller scan surface

## Core Manifest Should Include

- domains
- table families
- entity definitions
- signal stages
- tool registry summary
- key policies
- active modes
- package relationships

## Slice Manifest Should Include

- one domain
- local entities
- local states
- local tools
- local permissions
- local workflows
- local signals
- local observability pointers

## Knowledge Pack Structure

Suggested sections:

1. purpose
2. included sources
3. canonical entities
4. state transitions
5. signal map
6. tool map
7. policy constraints
8. examples
9. exclusions
10. open questions

## Anti-Patterns

Avoid:
- unbounded schema dumps
- doc packs without source references
- mixing deprecated and canonical rules
- broad scans when a slice exists

## Recommended Tables or Stores

- system_manifests
- system_manifest_slices
- system_knowledge_packs
- system_doc_sources
- system_pack_versions
