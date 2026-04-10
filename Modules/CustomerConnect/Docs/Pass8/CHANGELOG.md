# Pass 8 — Cleaning Operations Pack

This pass verticalises Titan Connect for cleaning operations by adding **status-change automation hooks** (arrival notice, post-clean review request, quality follow-up) and a safe test command.

## Added
- `Config/cleaning_ops.php` — feature flags + trigger statuses + delays
- `Services/Cleaning/CleaningOpsService.php` — builds & queues outbound messages into threads
- `Listeners/CleaningOpsStatusChangedListener.php` — consumes status-change events
- `Console/Commands/CleaningOpsTest.php` — dispatches a test status-change event

## Wired
- `CustomerConnectServiceProvider` now:
  - publishes + merges `customerconnect.cleaning_ops` config
  - listens for events:
    - `worksuite.booking.status_changed`
    - `worksuite.job.status_changed`
    - `cleaning.booking.status_changed`
    - `cleaning.job.status_changed`

## Notes
- Automations only run when `customerconnect.vertical.industry = cleaning` and `customerconnect.cleaning_ops.enabled = true`.
- All outbound messages are logged into existing threads/messages and sent via the existing channel sender.
