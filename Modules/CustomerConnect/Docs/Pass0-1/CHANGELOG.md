# Titan Connect — Pass 0 + Pass 1

## What changed
- Added strict tenant scoping across core CustomerConnect models using company_id + user_id (tenant_id = auth()->id()).
- Added migration to add missing user_id columns across CustomerConnect tables and add audit_meta to messages/deliveries.
- Hardened SMS/WhatsApp/Telegram sending to bootstrap Worksuite Sms module provider config (Twilio/Vonage/Msg91/Telegram token) before sending.
- Fixed a PHP syntax defect in `Jobs/SendThreadMessage.php` (stray private method outside class).
- Normalized Message model casting: `meta` (not meta_json) and added `audit_meta` cast.

## Compatibility
- If the Worksuite Sms module is not installed, sending continues to fail gracefully via notification channels (no hard dependency introduced).
