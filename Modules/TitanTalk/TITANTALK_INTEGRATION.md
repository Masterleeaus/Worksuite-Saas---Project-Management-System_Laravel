# TitanTalk Integration Report — Pass 3 (Stabilization)

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
