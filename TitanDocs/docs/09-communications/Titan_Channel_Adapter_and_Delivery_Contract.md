# Titan Channel Adapter and Delivery Contract

Defines the boundary between Titan communications orchestration and any specific channel provider or bridge.

## Purpose
A channel adapter converts canonical outbound and inbound message events into provider-specific formats while preserving audit and retry semantics.

## Adapter Responsibilities
- validate channel-specific destination format
- map canonical payload to provider payload
- return normalized provider response
- emit delivery events
- translate provider failures into canonical failure codes
- preserve raw provider payload where policy allows

## Canonical Outbound Contract
Required fields:
- outbound_id
- tenant_id
- thread_id
- channel
- destination
- message_type
- content_ref
- priority
- send_window
- correlation_id

## Canonical Inbound Contract
Required fields:
- inbound_id
- tenant_id
- channel
- source_identity
- provider_event_id
- content_ref
- received_at
- correlation_hints[]

## Delivery Statuses
- queued
- accepted_by_adapter
- sent_to_provider
- delivered
- read
- failed
- bounced
- throttled

## Adapter Capability Declarations
An adapter manifest should declare:
- supported_message_types[]
- supports_media yes/no
- supports_templates yes/no
- supports_read_receipts yes/no
- supports_thread_ids yes/no
- rate_limit_profile
- webhook_requirements[]

## Failure Mapping
Map provider errors into canonical families:
- destination_invalid
- auth_failed
- template_rejected
- throttled
- transient_network_error
- permanent_provider_error

## Retry Rules
Retries depend on failure family and policy.
Never blindly retry:
- invalid destination
- compliance rejection
- auth failures without credential refresh

## Observability
Every adapter should expose:
- acceptance rate
- delivery latency
- bounce/failure rates
- throttle events
- webhook health
