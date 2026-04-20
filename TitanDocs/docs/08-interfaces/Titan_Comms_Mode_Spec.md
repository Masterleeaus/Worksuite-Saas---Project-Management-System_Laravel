# Titan Comms Mode Specification

Defines the operational mode for inbox management, customer conversations, channel routing, and outbound communications.

## Purpose

Comms Mode is the communication control center.  
It unifies inbound and outbound interactions while preserving consent, identity, and auditability.

## Core Surfaces

- unified inbox
- thread detail
- channel status panel
- customer comms profile
- draft/review queue
- follow-up queue
- campaign status strip

## Core Objects

- thread
- message
- participant
- channel identity
- consent record
- delivery receipt
- outreach policy
- fallback chain

## Message Classes

- support
- service update
- quote follow-up
- invoice reminder
- urgent issue
- marketing
- review request

## Key Signals

- message_received
- reply_suggested
- reply_drafted
- send_approved
- send_blocked
- delivery_failed
- fallback_triggered
- thread_escalated

## Routing Rules

Routing must consider:
- preferred channel
- consent state
- urgency
- delivery history
- quiet hours
- customer relationship context

## Required Guards

- consent checks
- identity checks
- duplicate send protection
- approval for risky outbound messages
- cross-channel thread linking

## Recommended Tables

- comms_threads
- comms_messages
- comms_participants
- comms_channel_identities
- comms_delivery_events
- comms_fallback_rules
- comms_draft_queue
