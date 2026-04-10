# Titan Connect (CustomerConnect) — Pass 2: Unified Inbox UI

## Edited
- CustomerConnect/Http/Controllers/InboxController.php
- CustomerConnect/Http/Controllers/BulkActionsController.php
- CustomerConnect/Http/Controllers/ThreadController.php
- CustomerConnect/Entities/Thread.php
- CustomerConnect/Routes/web.php
- CustomerConnect/Resources/views/inbox/index.blade.php
- CustomerConnect/Resources/views/inbox/thread.blade.php
- CustomerConnect/Resources/views/sections/sidebar.blade.php

## Notes
- No database schema changes required for Pass 2 (uses existing `customerconnect_thread_reads.last_read_at` and `customerconnect_threads.archived_at`).
- Fixes multiple route/form field mismatches discovered during scan.
