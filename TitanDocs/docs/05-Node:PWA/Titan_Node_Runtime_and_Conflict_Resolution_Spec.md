# Titan Node Runtime and Conflict Resolution Spec

Defines the runtime responsibilities of client, server, and frontier nodes, and how conflicts are resolved in a privacy-first federated environment.

## Purpose

Titan is designed to run across multiple node classes while preserving privacy boundaries and coherent state. This spec defines what each node may do and how conflicting updates are reconciled.

## Node Classes

### Client node
A user-controlled device node.

Responsibilities:
- local-first interaction
- private memory and context retention
- local queueing when offline
- edge inference when available
- capture of user actions and field events

### Server node
A coordination and governance node.

Responsibilities:
- signal intake and routing
- shared tenant state coordination
- policy and governance enforcement
- aggregated observability
- sync mediation across devices

### Frontier node
An external model or specialized compute node.

Responsibilities:
- bounded inference
- transformation or enrichment tasks
- no authority over tenant state
- no direct system execution rights

## Runtime Contracts

Client nodes may:
- stage drafts
- capture private notes
- queue signals offline
- run local models
- render UI and gather approvals

Server nodes may:
- validate and govern signals
- publish approved state
- coordinate multi-device consistency
- expose health and audit surfaces

Frontier nodes may:
- receive bounded prompts/envelopes
- return candidate reasoning or content
- never become source of truth for state transitions

## Sync Units

Suggested sync units:
- signal envelopes
- domain object deltas
- snapshot envelopes
- approval decisions
- presence/session state
- attachment references and hashes

## Conflict Classes

Common conflict types:
- same field edited on multiple nodes
- stale offline approval collides with newer state
- duplicate object creation
- divergent workflow transitions
- attachment version mismatch
- conflicting route/schedule assignments

## Resolution Priority

Conflicts should be resolved using a bounded hierarchy, not naive last-write-wins alone.

Suggested precedence order:
1. explicit governance or approved state
2. domain lock/constraint rule
3. higher-authority node or actor
4. causal ordering / dependency validity
5. timestamp recency
6. user-assisted merge if unresolved

## Resolution Outcomes

A conflict may resolve to:
- auto-merge
- winner-selected
- split into separate records
- deferred for review
- rejected and rolled back
- escalated to user or operator

## Offline Behavior

When offline:
- local actions should be queued with envelopes
- approvals may be staged but not globally finalized unless policy allows
- object edits should preserve base version references
- eventual sync must detect whether the base version has drifted

## Privacy Boundary

Private data should remain local unless required for the chosen sync or inference path.

Prompting external frontier models should prefer:
- abstractions
- summaries
- identifiers stripped or replaced
- least-necessary context

## Observability

The system should record:
- node origin of updates
- sync lag
- conflict frequency
- auto-merge rate
- unresolved conflict queue
- privacy-sensitive routing choices
