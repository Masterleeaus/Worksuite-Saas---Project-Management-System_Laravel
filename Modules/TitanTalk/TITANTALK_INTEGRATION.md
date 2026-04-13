# TitanTalk Integration Report

## What was reused from existing Worksuite chat

- `App\Models\UserChat` — core DM system preserved untouched. TitanTalk extends over it.
- `App\Events\NewChatEvent` — existing DM broadcast kept. TitanTalk adds its own `TitanTalkMessageSent` event.
- `App\Notifications\NewChat` — DM notifications untouched. TitanTalk adds `TitanTalkNewMessage`.
- `App\Traits\HasCompany` — used on `TitanTalkRoom` and `TitanTalkMessage` for tenant isolation.
- `App\Models\BaseModel` — all models extend this.
- `App\Http\Controllers\AccountBaseController` — all web controllers extend this.

## What was reused from ChattingModule

- Channel/room concept (ChannelList → TitanTalkRoom)
- Channel membership concept (ChannelUser → TitanTalkRoomMember)
- Conversation concept (ChannelConversation → TitanTalkMessage)
- File attachment concept (ConversationFile → TitanTalkMessageFile)
- CompanyScoped pattern adapted to use core HasCompany trait

## What was reused from Communication

- Notification delivery pattern (database + mail via `via()` method)
- Per-user notification preferences concept → `titan_talk_notification_preferences`

## What was reused from Sms

- `notify_sms` flag in `TitanTalkNotificationPreference` — wire to Sms module's provider in future work.

## DB tables added

| Table | Purpose |
|---|---|
| titan_talk_rooms | Channels, rooms, DMs, ops rooms |
| titan_talk_room_members | Membership, roles, mute, last_read_at |
| titan_talk_messages | Room messages and thread replies |
| titan_talk_message_files | File attachments on messages |
| titan_talk_message_reactions | Emoji reactions |
| titan_talk_room_pins | Pinned messages per room |
| titan_talk_message_saves | User-saved messages |
| titan_talk_user_statuses | Presence + custom status |
| titan_talk_notification_preferences | Per-room notification preferences |

## Routes added

- `GET /account/titan-talk` — main app
- `GET /account/titan-talk/room/{room}` — room view
- `POST /account/titan-talk/rooms` — create room
- `POST /account/titan-talk/rooms/{room}/messages` — send message
- `POST /account/titan-talk/messages/{message}/thread` — thread reply
- `POST /account/titan-talk/messages/{message}/reactions` — add reaction
- `POST /account/titan-talk/messages/{message}/save` — save message
- `POST /account/titan-talk/messages/{message}/pin` — pin message
- `POST /account/titan-talk/status` — update presence/status
- `GET /account/titan-talk/search` — search
- Plus management routes for join/leave/invite/mute/unread-counts
- `routes/Titan/TitanTalk.routes.php` — Titan route bridge

## Permissions added

- `view_titan_talk`
- `create_titan_talk_rooms`
- `manage_titan_talk_rooms`
- `send_titan_talk_messages`
- `delete_titan_talk_messages`
- `manage_titan_talk_settings`

## Known follow-up work

1. Wire `notify_sms` preference to Sms module provider (Modules/Sms).
2. Add Echo/Pusher subscription on the client for `titan-talk.room.{id}` private channel.
3. Auto-create rooms on booking/project/issue created via Observers.
4. Add mention parsing (`@username`) and notification on mention.
5. Add search results UI dropdown in sidebar.
6. Add emoji picker UI.
7. Add typing indicator via Pusher presence channel.
8. Add admin settings panel for TitanTalk config.
9. Wire `view_titan_talk` permission check in RoomController constructor.
10. Add sidebar nav entry in Worksuite left menu.
