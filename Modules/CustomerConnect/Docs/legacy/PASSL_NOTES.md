# PASS L — Premium Performance + Privacy + Retention

This pass adds:
- Guarded indexes + archived_at columns for inbox/campaign tables
- Retention command: `php artisan customerconnect:retention`
- GDPR-style contact export (CSV): `customerconnect.privacy.contact.export`

Design notes:
- All migrations are defensive and avoid `after()` usage.
- No sidebar mutations; no heavy DI during rendering.
- Export endpoint is account-prefixed and should be policy-gated in your app if needed.
