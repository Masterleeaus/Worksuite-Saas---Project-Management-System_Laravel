# Titan Customer and Channel Consent Model

Defines how Titan stores communication permissions, quiet hours, opt-in state, and allowed automation by channel.

## Purpose

Titan must not treat all channels as interchangeable.  
Consent, timing, channel purpose, and message class determine whether a communication is allowed.

## Consent Dimensions

- customer
- tenant
- channel
- message_type
- time_window
- jurisdiction
- source
- proof

## Core Consent States

- opted_in
- opted_out
- transactional_only
- marketing_only
- service_updates_only
- unknown
- blocked

## Channel Examples

- email
- SMS
- WhatsApp
- Telegram
- Messenger
- voice
- push

## Message Classes

- invoice
- reminder
- schedule_update
- quote_followup
- marketing
- support
- review_request
- urgent_service_issue

## Required Consent Record

- consent_id
- tenant_id
- customer_id
- channel
- message_class
- consent_state
- captured_at
- source_type
- source_reference
- expires_at
- quiet_hours
- legal_basis
- last_used_at

## Routing Rules

Routing must consider:
- explicit preference
- legal allowance
- delivery history
- quiet hours
- urgency
- fallback policy
- language or locale preference

## Hard Blocks

Do not send if:
- channel is opted out
- quiet hours block message class
- proof of consent required but missing
- policy marks outreach class restricted

## Review Rules

High-sensitivity sends may still require approval even with consent:
- bulk outreach
- promotional campaigns
- voice calls
- repeated follow-ups inside short windows

## Recommended Tables

- comms_customer_consents
- comms_consent_events
- comms_quiet_hours
- comms_channel_preferences
- comms_outreach_limits
