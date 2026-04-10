# Changelog — Pass 2 (Unified Inbox UI)

## Added
- Inbox tabs: All / Unread / Archived
- Per-thread unread indicator derived from `last_message_at > last_read_at`
- Thread archive/unarchive (single thread + bulk)
- Sidebar quick links for Inbox tabs

## Fixed
- Inbox list route now points to `customerconnect.inbox.threads.show`
- Thread reply form now submits `message` correctly (controller supports legacy `body` for safety)
- Assignment field standardized to use `assigned_to` (matches DB schema)
- Bulk actions now tenant-scope updates (company/user) and support archive/unarchive

## Behavioral changes
- Opening a thread marks it as read for the current user.
