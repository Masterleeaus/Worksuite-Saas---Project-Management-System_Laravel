# CustomerConnect — Ops

## Commands
Run due campaigns/deliveries:
```bash
php artisan customerconnect:process-due
```

Warm unread badge cache:
```bash
php artisan customerconnect:cache-unread
```

SLA breach check (creates alerts):
```bash
php artisan customerconnect:sla-check --minutes=60
```

Retention (archive + optional delete):
```bash
php artisan customerconnect:retention --archive-days=90 --delete-days=365
# archive only
php artisan customerconnect:retention --archive-days=90 --no-delete
```

## Telegram alerts (optional)
If set, SLA/incident alerts can be pushed via the existing SMS module Telegram channel.

Environment:
- `CUSTOMERCONNECT_ALERT_TELEGRAM_CHAT_ID=...`
