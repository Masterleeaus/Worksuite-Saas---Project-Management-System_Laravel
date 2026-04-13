# TitanTalk Integration Report — Pass 2

## What was reused from Worksuite core DM system

| Component | How TitanTalk reuses it |
|---|---|
| `App\Models\UserChat` | DM rooms mirror messages into `users_chat` via `TitanTalkService::mirrorToUserChat()` so both TitanTalk and `/messages` inbox stay in sync |
| `App\Events\NewChatEvent` | Kept untouched; UserChat observer fires this when a mirrored DM message is saved |
| `App\Observers\NewChatObserver` | Kept untouched; fires `company_id` on `creating` for mirrored messages |
| `App\Models\UserchatFile` | Not duplicated; TitanTalk attachments use the same storage but stored in `titan_talk_message_files` |
| `App\Helper\Files::uploadLocalOrS3` | **Directly reused** in `TitanTalkService::attachFile()` — same local/S3/DO/Wasabi dispatch |
| `App\Notifications\NewChat` (pattern) | `TitanTalkNewMessage` mirrors the via() pattern: database + mail + OneSignal push + Beams push |
| `App\Events\NewMentionChatEvent` (pattern) | `TitanTalkMentionEvent` mirrors pattern for @mention private channel broadcast |
| `NewChatObserver::created()` mention logic | `TitanTalkService::parseMentions()` mirrors the @mention extraction and fires `TitanTalkMentionEvent` |
| `pusher_settings()` / `push_setting()` | Called inside `TitanTalkNewMessage::via()` and view Echo subscription — reuses existing Pusher config |
| `App\Http\Controllers\AccountBaseController` | All TitanTalk web controllers extend this |
| `App\Models\BaseModel` | All TitanTalk models extend this |
| `App\Traits\HasCompany` | `TitanTalkRoom` + `TitanTalkMessage` use it for company_id scoping |

## What was reused from ChattingModule

| Component | How TitanTalk reuses it |
|---|---|
| ChatRoom::booking_id / room-per-business-object concept | `TitanTalkRoom.reference_id + reference_type` follows this pattern for all business room types |
| `BookingChatController` attachment pattern (`private` disk) | `TitanTalkService::attachFile()` uses `Files::uploadLocalOrS3` (equivalent) |
| ChatRoom company_id isolation | `TitanTalkRoom` uses same `HasCompany` + `accessibleByUser()` scope |
| booking-linked room idempotency | `TitanTalkService::autoCreateRoom()` checks existing reference before creating |
| Group room membership | `TitanTalkRoomMember` reuses the same member-per-room concept |
| ChannelUser role pattern | `TitanTalkRoomMember.role` (owner/admin/member) mirrors ChannelUser |
| `ChatRoom::member_ids` concept | Replaced with a proper `titan_talk_room_members` pivot table (more relational, avoids JSON) |

## What was reused from Communication

| Component | How TitanTalk reuses it |
|---|---|
| `Communication::$fillable` fields | `TitanTalkService::logToCommunication()` writes `type=chat` records with `from_user_id`, `to_user_id`, `body`, `subject`, `sent_at` |
| `CommunicationAutomation.trigger_event` | Signals `titan_talk_mention`, `titan_talk_room_message` can be registered as trigger events for Communication automations |
| `TemplateController` type=chat | Communication templates of `type=chat` can be consumed by TitanTalk in future (e.g. welcome message on room join) |
| Notification delivery abstraction | `TitanTalkNewMessage::via()` follows the same pattern as Communication module's notification delivery |

## What was reused from Sms

| Component | How TitanTalk reuses it |
|---|---|
| `SmsNotificationSlug` enum | Added 3 new cases: `TitanTalkMention`, `TitanTalkDispatchAlert`, `TitanTalkIssueAlert` |
| `CleaningNotificationService::send()` | **Directly called** from `TitanTalkService::sendSmsEscalation()` with the `titan-talk-mention` slug |
| `SmsSettingTrait` patterns | SMS dispatch fully delegated to `CleaningNotificationService` — no duplication |
| `SmsTitanTalkBridgeTrait` | Pre-existing bridge trait updated (slug values now match new enum cases) |
| Opt-out / channel preference logic | Inherited via `CleaningNotificationService` (handles opt-outs automatically) |

## Integration architecture summary

```
TitanTalk room message
    │
    ├─→ TitanTalkMessageSent event → broadcast(PrivateChannel titan-talk.room.{id})
    │      └─→ Echo JS client appends message in real-time (reuses Pusher stack)
    │
    ├─→ TitanTalkNewMessage notification → database + mail + OneSignal/Beams push
    │      └─→ via() reuses push_setting() / email_notifications (same as NewChat)
    │
    ├─→ @mention detected → TitanTalkMentionEvent
    │      ├─→ broadcast(PrivateChannel titan-talk.user.{id}) — mention badge in client
    │      └─→ TitanTalkSmsListener → TitanTalkService::sendSmsEscalation()
    │               └─→ Sms\CleaningNotificationService::send() (optional, configurable)
    │
    ├─→ TitanTalkService::mirrorToUserChat() [DM rooms only, if mirror_dm_to_userchat=true]
    │      └─→ UserChat::save() → NewChatObserver fires → Worksuite /messages inbox updated
    │
    └─→ TitanTalkService::logToCommunication()
           └─→ Communication\Entities\Communication::create() (audit trail)
```

## Auto-room creation (observer-triggered)

| Business event | Room type | Observer method | Guarded by |
|---|---|---|---|
| `App\Models\Project::created` | `project` | `onProjectCreated` | `class_exists(Project::class)` |
| `App\Models\Task::created` where `task_type=booking` | `booking` | `onTaskCreated` | `class_exists(Task::class)` |
| `App\Models\Ticket::created` | `issue` | `onTicketCreated` | `class_exists(Ticket::class)` |
| `FSMCore\Models\FSMOrder::created` | `service_job` | `onFsmOrderCreated` | `class_exists(FSMOrder::class)` |
| `BookingModule\Models\CleaningBooking::created` | `booking` | `onCleaningBookingCreated` | `class_exists(CleaningBooking::class)` |

All observers are safe — wrapped in `try/catch(\Throwable)` to never break the original model save.
All can be disabled per-type via `titantalk.auto_create_rooms` config or `.env`:

```
TITANTALK_AUTOROOM_BOOKING=false
TITANTALK_AUTOROOM_SERVICE_JOB=false
TITANTALK_AUTOROOM_PROJECT=false
TITANTALK_AUTOROOM_ISSUE=false
```

## What remains intentionally separate

| Item | Reason |
|---|---|
| `users_chat` (core DM table) | Not replaced — TitanTalk adds its own `titan_talk_messages` for room messages |
| `/messages` Worksuite inbox | Kept as-is; DM rooms optionally mirror to it via `mirrorToUserChat()` |
| ChattingModule `chat_rooms` table | Kept as-is; TitanTalk uses `titan_talk_rooms` (richer schema with roles, reactions, pins, saves, status) |
| Communication OTP/email flows | Orthogonal; TitanTalk only uses Communication for audit logging and template metadata |
| SMS general delivery (Twilio) | Delegated entirely to `CleaningNotificationService`; no Twilio code in TitanTalk |

## New files added in this pass

| File | Purpose |
|---|---|
| `Services/TitanTalkService.php` | Central integration hub: DM bridge, file upload, mention parsing, auto-room, SMS escalation, Communication logging |
| `Events/TitanTalkMentionEvent.php` | Broadcast @mention to user's private channel |
| `Observers/TitanTalkAutoRoomObserver.php` | Auto-create rooms on Project/Task/Ticket/FSMOrder/CleaningBooking created |
| `Listeners/TitanTalkSmsListener.php` | SMS escalation on mention via Sms module |

## Files modified in this pass

| File | Changes |
|---|---|
| `Http/Controllers/MessageController.php` | Uses `TitanTalkService` for uploads/mentions/DM mirror/Communication logging; fires `TitanTalkMentionEvent` |
| `Http/Controllers/RoomController.php` | Uses `TitanTalkService::resolveDmRoom()`; handles `?dm_user_id=` redirect; dedup slug on create |
| `Notifications/TitanTalkNewMessage.php` | Reuses `push_setting()` / `email_notifications` / OneSignal / Beams (mirrors `NewChat` pattern) |
| `Providers/TitanTalkServiceProvider.php` | Registers `TitanTalkService` singleton; registers event listeners; registers model observers |
| `Config/config.php` | Adds `auto_create_rooms`, `mirror_dm_to_userchat`, `sms_escalation` config |
| `Resources/views/app.blade.php` | Adds Echo/Pusher private channel subscription; renders search results dropdown |
| `Modules/Sms/Enums/SmsNotificationSlug.php` | Adds `TitanTalkMention`, `TitanTalkDispatchAlert`, `TitanTalkIssueAlert` cases |

## Known follow-up items

1. Register `titan-talk-mention` in `SmsNotificationSetting` seed so the `send_sms` flag can be toggled from admin.
2. Add TipTap / rich-text editor integration with `@mention` autocomplete (mirrors TitanZero Canvas TipTap usage).
3. Add typing indicator via Pusher presence channel (`titan-talk.room.{id}` presence).
4. Add admin settings page for TitanTalk (mirror_dm_to_userchat, sms_escalation, auto_create_rooms toggles).
5. Add sidebar nav entry in Worksuite left menu via `MenuService`.
6. Add `view_titan_talk` permission check in `RoomController::__construct()` middleware.
7. Wire `notify_sms` preference in `TitanTalkNotificationPreference` to SMS escalation config.
8. Emoji picker UI (client-side only).
9. Full E2E tests for DM mirror, auto-room, and mention SMS flow.
