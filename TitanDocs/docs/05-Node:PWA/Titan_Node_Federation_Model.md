# Titan Node Federation Model

Defines synchronization between client, server, and frontier nodes.

## Node Types
- client_node
- server_node
- frontier_node

## Sync Rules
- local-first resolution
- manifest-scoped sync
- envelope-limited propagation

## Conflict Resolution
- timestamp priority
- authority weighting
- sentinel override

## Trust Boundaries
Client retains private data sovereignty.
Server coordinates signals.
Frontier models provide inference only.
