# Titan Channel Delivery State Model

Defines canonical delivery states across SMS, WhatsApp, Telegram, Messenger, Email, Push, and Voice.

## Purpose

Each channel has provider-specific status codes, but Titan needs one canonical model for routing, retry, audit, and user-visible state.

## Canonical Delivery States

- drafted
- queued
- handed_off
- provider_accepted
- in_transit
- delivered
- opened
- interacted
- failed
- bounced
- blocked
- expired
- cancelled

## Per-Channel Notes

### SMS
Common states:
- queued
- sent
- delivered
- failed
- undelivered

### Email
Common states:
- queued
- delivered
- bounced
- opened
- clicked
- complaint

### WhatsApp / Messenger / Telegram
Common states:
- queued
- sent
- delivered
- read
- failed
- blocked

### Voice
Common states:
- initiated
- ringing
- answered
- completed
- busy
- no_answer
- failed

## Mapping Rules

Provider-specific codes must map into:
- canonical_state
- confidence
- terminal_or_not
- user_visible_label
- retry_eligible

## Terminal States

Terminal states include:
- delivered
- failed
- bounced
- blocked
- expired
- cancelled
- completed

## Retry Guidance

Retry should depend on:
- channel
- message class
- provider failure code
- consent state
- quiet hours
- fallback availability

## Required Fields

- delivery_event_id
- tenant_id
- message_id
- channel
- provider_name
- provider_status
- canonical_state
- occurred_at
- terminal
- retry_eligible
- fallback_triggered

## Recommended Tables

- comms_delivery_events
- comms_delivery_mappings
- comms_provider_failures
- comms_retry_decisions
