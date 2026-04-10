# PASS I inbound auto-assign hook integration

In each inbound webhook controller method AFTER you create/resolve the thread id, call:

```php
app(\Modules\CustomerConnect\Services\Inbox\InboundAutoAssignHook::class)
    ->ensureAssigned($threadId, (int)$companyId);
```

This keeps assignment deterministic and avoids needing cron.
