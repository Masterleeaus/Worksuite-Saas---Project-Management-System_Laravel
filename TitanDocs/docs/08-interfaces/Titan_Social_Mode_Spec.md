# Titan Social Mode Specification

Defines the operational mode for publishing, campaign workflows, audience surfaces, and social content operations.

## Purpose

Social Mode remains a first-class operational mode rather than being collapsed into generic comms.  
It manages outbound social workflows, repurposing flows, approval, and performance review.

## Core Objects

- draft
- campaign
- channel target
- asset
- approval item
- schedule slot
- performance snapshot

## Core Surfaces

- draft queue
- campaign board
- content calendar
- asset picker
- approval queue
- performance summary
- repurposing pipeline

## Key Signals

- draft_created
- draft_ready_for_review
- publish_approved
- publish_blocked
- publish_scheduled
- publish_failed
- engagement_snapshot_recorded
- repurpose_suggested

## Required Guards

- brand/approval controls
- channel capability checks
- schedule collision handling
- asset availability checks
- per-channel adapter compliance

## Recommended Tables

- social_drafts
- social_campaigns
- social_channel_targets
- social_assets
- social_schedule_slots
- social_publish_logs
- social_performance_snapshots
