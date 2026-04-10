# CustomerConnect PASS I (Premium Polish)

## What this pass adds
- Thread event timeline UI (message_events)
- SLA metrics (first response time, awaiting response)
- Telegram alert notifier service (optional)

## Route change required
Paste `Routes/PASSI_routes_snippet.php` into `Routes/web.php` within the CustomerConnect account group.

## Optional env/config
Set a Telegram chat for alerts:
- CUSTOMERCONNECT_ALERT_TELEGRAM_CHAT_ID=<chat_id>

This pass does not enable alerting by default; it adds the notifier.
