# Titan Communications Orchestration and Fallback Model

Defines how Titan Omni chooses channels, threads conversations, escalates delivery attempts, and preserves a unified communication history.

## Purpose

The orchestration layer ensures a message is sent through the best available channel while preserving user intent, customer preferences, compliance rules, and delivery visibility.

## Core Objectives

- choose the right channel for the context
- preserve one canonical conversation thread across channels
- avoid duplicate sends
- support fallback when delivery fails
- respect channel policy, consent, and priority
- keep the inbox unified even when transport is fragmented

## Canonical Terms

- `conversation` — the user-facing unified communication history
- `thread` — a related sequence of messages under a conversation
- `attempt` — one delivery attempt on one channel
- `channel` — SMS, WhatsApp, Telegram, Messenger, Email, Push, Voice, etc.
- `routing plan` — ordered candidate channels for a message
- `fallback` — a next attempt using another channel or later retry path

## Routing Inputs

Channel selection should consider:
- customer channel preferences
- verified reachable channels
- consent status per channel
- urgency level
- message type
- attachment/media needs
- automation confidence
- business hours and local time
- previous delivery success/failure history
- cost sensitivity and channel pricing
- tenant-specific policy

## Message Classes

Suggested classes:
- transactional
- appointment/reminder
- urgent operational alert
- sales/follow-up
- support/reply
- system escalation
- internal team communication

Each class may define:
- allowed channels
- forbidden channels
- preferred channels
- fallback ladder
- delivery SLA target

## Routing Plan Generation

A routing plan should produce an ordered list of channel candidates with scores.

Score factors may include:
- reachability confidence
- consent confidence
- historical deliverability
- freshness of customer activity on the channel
- attachment capability
- expected response likelihood
- latency target fit
- cost fit

## Fallback Rules

Fallback should only occur when:
- initial delivery failed
- initial delivery timed out
- message was not acknowledged within policy window
- channel policy forbids the attempt
- recipient is unreachable on the chosen transport

Fallback should not occur when:
- duplicate acknowledgment already exists
- a human took over the thread
- the message class forbids multi-channel repeats
- consent does not permit alternate channels

## Escalation Ladder

Example pattern:
1. preferred direct channel
2. secondary consented channel
3. low-friction email fallback
4. internal alert to operator
5. optional voice escalation for high urgency

Escalation ladders should be tenant-configurable but bounded by policy.

## Threading Model

All outbound and inbound attempts should map to one canonical conversation id where possible.

The thread model should preserve:
- canonical conversation id
- participant identity mapping
- source channel message id
- transport metadata
- delivery state timeline
- human takeover state
- automation state

## Canonical Delivery States

- queued
- dispatched
- accepted_by_provider
- delivered
- read
- replied
- failed
- timed_out
- suppressed
- escalated

## Human Takeover Rule

When a human takes over a conversation:
- automation may be paused or narrowed
- fallback rules may be suppressed
- channel switching should become more conservative
- notes and takeover reason must be recorded

## Duplicate Prevention

To avoid double sends:
- use attempt correlation ids
- maintain message fingerprinting
- suppress same-intent repeats within configurable windows
- cancel downstream fallbacks when positive delivery evidence arrives

## Inbox Requirement

The unified inbox must display:
- one conversation timeline
- channel badges per message
- attempt history when needed
- status transitions
- delivery failures and escalation notes
- operator ownership/takeover state

## SLA Tiers

Suggested delivery SLA categories:
- immediate
- near_real_time
- same_day
- low_priority

Each SLA tier may constrain allowable channels and fallback timing.

## Compliance and Consent

The orchestration layer must check:
- opt-in / opt-out status
- channel-specific policy
- quiet hours
- region/legal constraints
- business/tenant communication rules

## Audit Requirements

Every routing and fallback decision should record:
- message class
- chosen channel and score
- alternate channels considered
- reason for fallback or suppression
- operator override if any
- timestamps for each attempt stage
