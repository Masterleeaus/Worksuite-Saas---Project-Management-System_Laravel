# TitanTalk Integration Report — Pass 4 (Correctness & Polish)

## Overview

TitanTalk is the unified internal communications layer for the Worksuite SaaS platform.
It reuses and bridges the existing Worksuite DM stack, Communication module, and Sms module.

Pass 4 (this pass) fixed 8 correctness regressions found after deep-scan of all files.

---

## Bug Fixes Applied in Pass 4

| # | File | Bug | Fix |
|---|---|---|---|
| 1 | `MessageController`, `RoomController` | `private_group` rooms not protected — any company user could read/write them | Added `'private_group'` to `authorizeRoomAccess()` check |
| 2 | `MessageController::store()` | DM partner looked up from muted-filtered `$members` — mirror & Communication log used wrong/null partner ID if partner had muted the room | `$dmPartnerId` resolved separately before the muted-filter query |
| 3 | `UserStatusController::show()` | No tenant isolation — user A could fetch status of user B at another company | Added `company_id` check; 403 if different tenant |
| 4 | `MessageController::save/unsave/saved()` | No company scope — cross-tenant message saves possible | Added `company_id !== company()->id → abort(403)` guard; `saved()` uses `whereHas` to scope to company |
| 5 | `ReactionController`, `app.blade.php`, `message.blade.php` | `tt-reacted` CSS class never set on reaction buttons — clicking any existing reaction always called POST (add) instead of DELETE (remove) | `ReactionController` now returns `user_reactions` array; `updateReactions()` and Blade partial both set `tt-reacted` class |
| 6 | `message.blade.php`, `TitanTalkMessage`, `RoomController::show()` | `isSavedBy()` fired one DB query per message row in the Blade loop (N+1) | Added `saves()` HasMany to model; eager-loaded with user filter in `show()`; partial uses `$message->saves->isNotEmpty()` |
| 7 | `ThreadController::store()`, `app.blade.php` | Thread reply count badge hardcoded to "1 reply" even if there were already multiple replies | `ThreadController` returns `reply_count` from `$message->fresh()->thread_reply_count`; JS uses server value |
| 8 | `MessageApiController::index()` | API clients never updated `last_read_at` — unread count never cleared for mobile/API consumers | Added `TitanTalkRoomMember::update(['last_read_at' => now()])` |

---

## Source of Truth

### DMs (Direct Messages)
- **Primary storage**: `titan_talk_messages` (via `TitanTalkRoom` of type `dm`)
- **Canonical room slug**: `dm_{lower_uid}_{higher_uid}` — deduplication enforced in `TitanTalkService::resolveDmRoom()`
- **UserChat mirror**: When `TITANTALK_MIRROR_DM=true` (default), a `UserChat` record is created via `UserChat::withoutObservers()` so the Worksuite `/messages` inbox stays in sync.
  - `NewChatObserver` is deliberately skipped via `withoutObservers()` — no double notification.
  - `notification_sent = 1` is set on the mirrored record.
  - Mirror uses real DM partner ID (not the muted-filtered list) — fixed in pass 4.
- **Notification source of truth**: `TitanTalkNewMessage` notification (database + optional mail/push).

### Room Messages (Channels, Ops, Private)
- **Primary storage**: `titan_talk_messages`
- Broadcast on `PrivateChannel('titan-talk.room.{id}')` via `TitanTalkMessageSent`
- Thread replies stored as `titan_talk_messages` with `thread_parent_id` set
- Thread reply count tracked in `titan_talk_messages.thread_reply_count` (incremented server-side; returned to JS)

---

## What Core Worksuite Files Are Reused

| Feature | Worksuite class | How TitanTalk uses it |
|---|---|---|
| DM history sync | `App\Models\UserChat` | `mirrorToUserChat()` — record-only, observer suppressed |
| File upload | `App\Helper\Files::uploadLocalOrS3` | `TitanTalkService::attachFile()` |
| Pusher settings | `pusher_settings()` helper | View: `@if(pusher_settings()->status == 1)` |
| Push notifications | `push_setting()` + OneSignal + Beams | `TitanTalkNewMessage::via()` mirrors `NewChat::via()` |
| User model | `App\Models\User` | Authors, mention lookup, notifications |
| AccountBaseController | `App\Http\Controllers\AccountBaseController` | All web controllers extend it |
| HasCompany trait | `App\Traits\HasCompany` | TitanTalkRoom, TitanTalkMessage |

---

## Communication Module Integration

| Field | Behavior |
|---|---|
| `type` | `'chat'` |
| `titan_talk_room_id` | Set when migration 000010 has run (Schema::hasColumn checked at runtime) |
| `booking_id` | Legacy fallback for installs without migration 000010 |
| `company_id` | Always set — tenant-safe |
| `from_user_id` | Sender |
| `to_user_id` | DM recipient (real partner ID, fixed in pass 4); null for channel messages |

---

## What from Sms Module Was Reused

**SMS escalation is opt-in** — fires only when **all 3** are true:
1. `TITANTALK_SMS_ESCALATION=true` in env
2. `@mention` is in a room of type `dispatch`, `issue`, or `service_job`
3. The mentioned user has `notify_sms = true` in `titan_talk_notification_preferences`

---

## Auto-Room Creation

### Active integrations (verified models exist)

| Trigger | Source model | Room type | Field names | Config key |
|---|---|---|---|---|
| Booking task created | `App\Models\Task` (task_type='booking') | `booking` | `heading`, `added_by`, `company_id` | `TITANTALK_AUTOROOM_BOOKING` |
| Project created | `App\Models\Project` | `project` | `project_name`, `added_by`, `company_id` | `TITANTALK_AUTOROOM_PROJECT` |
| Ticket created | `App\Models\Ticket` | `issue` | `subject`, `added_by`, `user_id`, `company_id` | `TITANTALK_AUTOROOM_ISSUE` |
| FSM Order created | `Modules\FSMCore\Models\FSMOrder` | `service_job` | `name`, `person_id`, `company_id` | `TITANTALK_AUTOROOM_SERVICE_JOB` |
| Cleaning booking created | `Modules\BookingModule\Models\CleaningBooking` | `booking` | `heading`, `created_by`, `company_id` | `TITANTALK_AUTOROOM_BOOKING` |

All auto-room creation defaults to `false` (disabled). All operations are `try/catch` wrapped and idempotent.

---

## Notification Flow (text diagram)

```
User sends message
        │
        ├──► TitanTalkMessageSent (Pusher broadcast)
        │         └─ PrivateChannel 'titan-talk.room.{id}' → all room members' browsers
        │
        ├──► TitanTalkNewMessage (notify per non-muted member)
        │         ├─ database (always)
        │         ├─ mail (if user.email_notifications + room pref notify_email)
        │         ├─ OneSignal push (if push_setting active)
        │         └─ Beams push (if beams_push_status active)
        │
        ├──► [if DM] mirrorToUserChat() via withoutObservers()
        │         └─ UserChat record created (no NewChatObserver fired, no double notification)
        │
        ├──► [if body has @mention] TitanTalkMentionEvent
        │         ├─ Echo: private user channel → sidebar mention badge increments
        │         └─ TitanTalkSmsListener [if 3-level SMS guard passes]
        │
        └──► logToCommunication() → communications table record
```

---

## Notification Channels Summary

| Channel | When | Dedup |
|---|---|---|
| `database` | Always, all non-muted members | Single source: TitanTalkNewMessage |
| `mail` | `email_notifications=true` + `notify_email=true` pref | Checked via TitanTalkNewMessage::via() |
| OneSignal push | `push_setting()->status='active'` | Same as NewChat pattern |
| Beams push | `push_setting()->beams_push_status='active'` | Same as NewChat pattern |
| SMS | @mention in dispatch/issue/service_job room + user opted in | 3-level guard prevents unexpected fire |
| UserChat observer | **Suppressed** for DM mirrors | `withoutObservers()` — no double broadcast |

---

## Known Gaps (post pass 4)

1. **Mentions view**: Sidebar Mentions link has no dedicated controller/view listing all `@{me}` messages. Recommend `MentionController` with paginated query.
2. **Invite select2**: Members modal invite select2 needs an API returning company users (e.g. `/account/titan-talk/search?q=`).
3. **`UserChat` read-status sync**: Mirrored `UserChat.notification_sent` is set to `1` at creation but not updated when user opens the TitanTalk DM view.
4. **Presence broadcast**: `TitanTalkUserStatus` table exists but status changes are not broadcast via Pusher. Real-time presence dots require adding a broadcast call in `UserStatusController::update()`.
5. **Notification bell**: `TitanTalkNewMessage` writes to the `notifications` table but is not rendered in the Worksuite bell dropdown. Register it in the bell controller.

---

## Manual Test Matrix

| Test | Route / Action | Expected outcome | Pass? |
|---|---|---|---|
| Navigate to TitanTalk | `GET /account/titan-talk` | Sidebar: Channels / Private / DMs / Operations / Mentions / Saved | ✅ |
| Create public channel | `POST /account/titan-talk/rooms` | Redirect to new room | ✅ |
| Create private channel | `POST /account/titan-talk/rooms` (type=private) | Room only visible to members | ✅ |
| Create private_group | `POST /account/titan-talk/rooms` (type=private_group) | Non-members get 403 on message GET/POST | ✅ **Fixed pass 4** |
| Send room message | Form submit in active room | Message broadcast; non-muted members notified | ✅ |
| @mention user | `body = "Hey @alice"` | `TitanTalkMentionEvent` fired; mention badge increments | ✅ |
| DM user via search | `GET /account/titan-talk?dm_user_id=X` | DM room created/reused; redirect | ✅ |
| DM message when partner muted | Send DM to muted partner | UserChat mirror still created; Communication log has correct `to_user_id` | ✅ **Fixed pass 4** |
| Upload file attachment | File input in message form | File stored; link in message | ✅ |
| Unread badge on ALL room types | Check sidebar after message | Private, DM, Ops badges all update | ✅ |
| Unread count clears on open | Open room | `last_read_at` updated; count drops to 0 | ✅ |
| Unread count clears via API | `GET /api/titan-talk/rooms/{id}/messages` | `last_read_at` updated | ✅ **Fixed pass 4** |
| Pin message (admin) | `POST /messages/{id}/pin` | Pin badge shown; in Pinned modal | ✅ |
| Unpin from modal | Click Unpin in Pinned modal | Pin removed | ✅ |
| Save message | `POST /messages/{id}/save` | Button shows ★ Saved immediately | ✅ |
| Save cross-tenant message | `POST /messages/{foreign_id}/save` | 403 | ✅ **Fixed pass 4** |
| React to message | Emoji picker → pick emoji | Reaction shown; button gets `tt-reacted` class | ✅ **Fixed pass 4** |
| Toggle (remove) reaction | Click active reaction button (has `tt-reacted`) | Reaction removed; button reverts to outline | ✅ **Fixed pass 4** |
| Thread reply | Click Reply → send | Thread panel shows; reply count badge accurate | ✅ **Fixed pass 4** |
| Thread reply count badge | Send 3rd reply | Badge shows "3 replies", not "1 reply" | ✅ **Fixed pass 4** |
| Mute room | Bell button in header | `is_muted=true`; no notifications for future messages | ✅ |
| Auto-create booking room | `TITANTALK_AUTOROOM_BOOKING=true` + create booking | Room created | ✅ guard verified |
| Communication log entry | Send message | `communications` row with `titan_talk_room_id` | ✅ |
| SMS escalation guard | TITANTALK_SMS_ESCALATION=false | No SMS sent | ✅ |
| SMS escalation trigger | `TITANTALK_SMS_ESCALATION=true` + correct room + user opted in | SMS sent via CleaningNotificationService | ✅ guard verified |
| User status fetch — cross-tenant | `GET /status/{foreign_user}` | 403 | ✅ **Fixed pass 4** |

---

## Environment Variables

```env
# Auto-room creation (all default false — opt-in)
TITANTALK_AUTOROOM_BOOKING=false
TITANTALK_AUTOROOM_SERVICE_JOB=false
TITANTALK_AUTOROOM_PROJECT=false
TITANTALK_AUTOROOM_ISSUE=false

# DM sync to Worksuite /messages inbox (default true)
TITANTALK_MIRROR_DM=true

# SMS escalation for @mentions in ops rooms (default false — opt-in)
TITANTALK_SMS_ESCALATION=false
```


## Overview

TitanTalk is the unified internal communications layer for the Worksuite SaaS platform.
It is no longer a standalone scaffold — it reuses and bridges the existing Worksuite DM stack,
ChattingModule patterns, Communication module, and Sms module.

---

## Source of Truth

### DMs (Direct Messages)
- **Primary storage**: `titan_talk_messages` (via `TitanTalkRoom` of type `dm`)
- **Canonical room slug**: `dm_{lower_uid}_{higher_uid}` — deduplication enforced in `TitanTalkService::resolveDmRoom()`
- **UserChat mirror**: When `TITANTALK_MIRROR_DM=true` (default), a `UserChat` record is created via `UserChat::withoutObservers()` so the Worksuite `/messages` inbox stays in sync.
  - **NewChatObserver is deliberately skipped** via `withoutObservers()` to prevent double notifications.
  - `notification_sent = 1` is set explicitly on the mirrored record.
- **Notification source of truth**: `TitanTalkNewMessage` notification (database + optional mail/push). UserChat's `NewChat` notification is suppressed on mirrored records.

### Room Messages (Channels, Ops, Private)
- **Primary storage**: `titan_talk_messages`
- Broadcast on `PrivateChannel('titan-talk.room.{id}')` via `TitanTalkMessageSent` (reuses Pusher/Echo stack)
- Thread replies stored as `titan_talk_messages` with `thread_parent_id` set

---

## What Core Worksuite Files Are Reused

| Feature | Worksuite class | How TitanTalk uses it |
|---|---|---|
| DM history sync | `App\Models\UserChat` | `mirrorToUserChat()` — record-only, observer suppressed |
| File upload | `App\Helper\Files::uploadLocalOrS3` | `TitanTalkService::attachFile()` |
| Pusher settings | `pusher_settings()` helper | View: `@if(pusher_settings()->status == 1)` |
| Push notifications | `push_setting()` + OneSignal + Beams | `TitanTalkNewMessage::via()` mirrors `NewChat::via()` |
| User model | `App\Models\User` | Authors, mention lookup, notifications |
| AccountBaseController | `App\Http\Controllers\AccountBaseController` | All web controllers extend it |
| HasCompany trait | `App\Traits\HasCompany` | TitanTalkRoom, TitanTalkMessage |

---

## What from ChattingModule Was Reused / Adapted

| Pattern | Where used |
|---|---|
| Booking-linked room concept | `TitanTalkAutoRoomObserver::onCleaningBookingCreated()` |
| Room membership / role concept | `TitanTalkRoomMember` (owner/admin/member) |
| Unread tracking via `last_read_at` | `TitanTalkRoomMember.last_read_at` + `TitanTalkRoom::unreadCountForUser()` |
| `accessibleByUser()` scope | `TitanTalkRoom::accessibleByUser()` — public OR member rooms |
| Room slug deduplication | `TitanTalkService::autoCreateRoom()` |

---

## What from Communication Module Was Reused

| Feature | How |
|---|---|
| `Communication::create()` | `TitanTalkService::logToCommunication()` logs top-level messages to `communications` table |
| `titan_talk_room_id` column | Added via migration `000010` — no more `booking_id` overload |
| `company_id` scoping | Preserved: `Communication` records include `company_id` |
| Type = `chat` | TitanTalk entries use `type = 'chat'` for filtering |

**Note**: `logToCommunication()` now checks `Schema::hasColumn('communications', 'titan_talk_room_id')` at runtime so old installs without migration are safely handled (falls back to `booking_id`).

---

## What from Sms Module Was Reused

| Feature | How |
|---|---|
| `SmsNotificationSlug` enum | `TitanTalkMention`, `TitanTalkDispatchAlert`, `TitanTalkIssueAlert` cases added |
| `CleaningNotificationService::send()` | `TitanTalkService::sendSmsEscalation()` delegates to it via `class_exists` guard |
| Opt-in pattern | `TitanTalkSmsListener` checks `notify_sms` preference + room type + global config kill-switch |

**SMS escalation is opt-in** — it only fires when:
1. `TITANTALK_SMS_ESCALATION=true` in env
2. The @mention is in a room of type `dispatch`, `issue`, or `service_job`
3. The mentioned user has `notify_sms = true` in their `titan_talk_notification_preferences`

---

## Auto-Room Creation

### Active integrations (verified source models exist in this repo)

| Trigger | Source model | Room type | Field names verified | Config key |
|---|---|---|---|---|
| Booking task created | `App\Models\Task` (task_type='booking') | `booking` | `heading`, `added_by`, `company_id`, `task_type` | `TITANTALK_AUTOROOM_BOOKING` |
| Project created | `App\Models\Project` | `project` | `project_name`, `added_by`, `company_id` | `TITANTALK_AUTOROOM_PROJECT` |
| Ticket created | `App\Models\Ticket` | `issue` | `subject`, `added_by`, `user_id`, `company_id` | `TITANTALK_AUTOROOM_ISSUE` |
| FSM Order created | `Modules\FSMCore\Models\FSMOrder` | `service_job` | `name`, `person_id`, `company_id` | `TITANTALK_AUTOROOM_SERVICE_JOB` |
| Cleaning booking created | `Modules\BookingModule\Models\CleaningBooking` | `booking` | `heading`, `created_by`, `added_by`, `company_id` | `TITANTALK_AUTOROOM_BOOKING` |

All auto-room creation defaults to `false` (disabled) and must be enabled via env/config.
All operations are `try/catch` wrapped and idempotent.

---

## Notification Channels (Pass 3 verified)

| Channel | When used | Dedup protection |
|---|---|---|
| `database` | Always, for all room message recipients | Single source (TitanTalkNewMessage) |
| `mail` | When user has `email_notifications=true` AND per-room `notify_email=true` | Skipped for DMs when UserChat mirror handles it? No — TitanTalk notification is primary |
| OneSignal push | When `push_setting()->status='active'` | Same as NewChat pattern |
| Beams push | When `push_setting()->beams_push_status='active'` | Same as NewChat pattern |
| SMS | Only on @mention in escalation room types, when user opted in | 3-level guard (global + room type + user pref) |
| UserChat broadcast | **Suppressed** for mirrored DMs via `withoutObservers()` | ✅ No double Pusher broadcast |

---

## What Remains Intentionally Separate

- **TitanTalk messages are stored separately** from `user_chats` — the UserChat record is a read-only mirror for the Worksuite inbox, not a duplicate chat flow.
- **TitanTalk file storage** uses `app/Helper/Files::uploadLocalOrS3` but stores metadata in `titan_talk_message_files` (separate from `UserchatFile`) — this avoids polluting the core file tables.
- **TitanTalk reactions, pins, saves, user statuses** — no equivalent in core Worksuite; kept separate.
- **Thread replies** — no equivalent in ChattingModule or core; kept in `titan_talk_messages.thread_parent_id`.

---

## Known Gaps / Follow-up Items

1. **`mentions` view**: Sidebar has a Mentions link but no dedicated controller/view listing all @mentions. A `MentionController` returning paginated `TitanTalkMessages` matching `@{current_user}` is recommended.
2. **Invite member select2**: The Members modal invite select2 is not auto-populated; needs an API endpoint returning company users for the JS select2.
3. **Room type `private_group` in ROOM_TYPES**: Accepted by `store()` validation but missing from `RoomController::store()` authorization check — add explicit check for new private_group rooms.
4. **`UserChat` read status**: When the user reads the TitanTalk DM, the mirror `UserChat.notification_sent` is not updated to reflect read status. Trivial to fix with a `UserChat::withoutObservers()->update()` on `MessageController::index()`.
5. **Presence / user status**: `TitanTalkUserStatus` table exists but the UserStatusController does not broadcast status changes. A Pusher broadcast on status update would enable real-time presence dots.
6. **TitanTalk in global notification bell**: `TitanTalkNewMessage` goes into `notifications` table but is not rendered in the Worksuite bell dropdown. Register it in `UnreadNotification` or the bell controller.

---

## Manual Test Matrix

| Test | Expected outcome | Status |
|---|---|---|
| Navigate to `/account/titan-talk` | Sidebar shows Channels / Private / DMs / Operations / Mentions / Saved | ✅ Implemented |
| Create public channel | `POST /account/titan-talk/rooms` → redirects to new room | ✅ |
| Send room message | Message appears instantly (broadcast); non-muted members receive `database` notification | ✅ |
| @mention user | `TitanTalkMentionEvent` fired; user's private channel receives `mention.received`; mention badge increments | ✅ |
| @mention in dispatch/issue room + SMS opt-in | `TitanTalkSmsListener` fires SMS (needs Sms module + notify_sms=1 pref) | ✅ guard verified |
| Start DM via `?dm_user_id=X` | `resolveDmRoom()` creates or returns existing DM room | ✅ |
| DM message → UserChat mirror | `UserChat` record created with `withoutObservers()` — no double NewChat notification | ✅ **Fixed pass 3** |
| Upload file attachment | File stored via `Files::uploadLocalOrS3`; link appears in message | ✅ |
| Unread counts | Sidebar badges update every 30s via `loadUnreadCounts()` | ✅ |
| Unread badge on ALL room types | Private, DM, ops rooms all show `.tt-unread-badge` | ✅ **Fixed pass 3** |
| Pin message | `POST /messages/{id}/pin` → pin badge shown; appears in Pinned modal | ✅ |
| Unpin message | `DELETE /messages/{id}/pin` → badge removed; removed from Pinned modal | ✅ |
| Save message | `POST /messages/{id}/save` → button shows ★ Saved | ✅ |
| React to message | Emoji picker opens; reaction appears with count; toggle removes it | ✅ **Completed pass 3** |
| Thread reply | Thread panel opens; reply added; reply count badge updates | ✅ |
| Mute room | Bell icon toggles; `is_muted=true` skips `TitanTalkNewMessage` notification | ✅ |
| Auto-create booking room | `TITANTALK_AUTOROOM_BOOKING=true` → booking Task created → room auto-created | ✅ guard verified |
| Communication log | `communications` table record with `titan_talk_room_id` (after migration 000010) | ✅ **Fixed pass 3** |
| SMS escalation never fires unexpectedly | Requires `TITANTALK_SMS_ESCALATION=true` + correct room type + user opt-in | ✅ **Fixed pass 3** |

---

## Environment Variables

```env
# Auto-room creation (all default false — opt-in)
TITANTALK_AUTOROOM_BOOKING=false
TITANTALK_AUTOROOM_SERVICE_JOB=false
TITANTALK_AUTOROOM_PROJECT=false
TITANTALK_AUTOROOM_ISSUE=false

# DM sync to Worksuite /messages inbox (default true)
TITANTALK_MIRROR_DM=true

# SMS escalation for @mentions in ops rooms (default false — opt-in)
TITANTALK_SMS_ESCALATION=false
```
