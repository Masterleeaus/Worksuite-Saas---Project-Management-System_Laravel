# Titan Inbox Threading and Channel Identity Model

Defines the canonical inbox/thread model across SMS, email, WhatsApp, Messenger, Telegram, push, voice follow-up, and future channels.

## Objectives
- preserve one conversation history per counterpart or topic
- unify channel-specific identifiers
- avoid duplicate threads
- support handoff between channels
- maintain auditability for every outbound and inbound event

## Canonical Objects
### ContactIdentity
Represents a person or endpoint candidate.
Fields may include:
- contact_id
- tenant_id
- display_name
- phones[]
- emails[]
- channel_handles[]
- confidence_links[]

### ChannelIdentity
Represents a single addressable endpoint on a specific channel.
Examples:
- E.164 phone
- email address
- WhatsApp JID
- Telegram chat id
- Messenger PSID

### ConversationThread
The canonical thread abstraction.
Fields:
- thread_id
- tenant_id
- primary_contact_id
- subject_hint
- channel_memberships[]
- status
- assigned_to
- priority
- last_activity_at

### MessageEvent
The raw inbound/outbound message or delivery event.
Fields:
- event_id
- thread_id
- channel
- direction
- provider_message_id
- content_ref
- sent_at
- delivered_at
- read_at
- failure_code

## Thread Association Rules
Associate an inbound event using:
1. exact provider thread identifier if present
2. exact channel identity match
3. recent contact-thread linkage
4. fallback heuristic match
5. unresolved queue if confidence too low

## Cross-Channel Merging
Merge only when:
- contact identity confidence passes threshold
- tenant scope matches
- human-readable timeline remains intelligible
- protected communication contexts are not mixed incorrectly

## Do Not Merge Cases
- likely shared business phone
- ambiguous email alias
- disputed contact linkage
- regulated conversation partition required

## Thread States
- open
- waiting_on_us
- waiting_on_customer
- snoozed
- escalated
- resolved
- archived

## Assignment Rules
Threads can be assigned by:
- role queue
- campaign owner
- customer success owner
- dispatcher
- finance queue
- AI proposal only (not auto-executed unless allowed)

## Identity Confidence
Identity links should store:
- match_type
- match_score
- evidence_basis
- last_confirmed_at

## Audit
Never lose channel-native IDs even after merge.
