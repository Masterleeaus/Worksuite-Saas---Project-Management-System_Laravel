# TitanReach — OmniChannel Inbox & Campaign Manager

TitanReach is the omni-channel outreach and inbox module for Worksuite.
It unifies WhatsApp, SMS, Telegram, and voice calls into a single inbox
and campaign manager with optional AI-powered reply suggestions.

## Features

| Area | Details |
|------|---------|
| **Inbox** | Unified view across channels; filter by channel/status/search; AI suggest reply (TitanZero or configured gateway); assign conversations; update status |
| **Campaigns** | Multi-channel campaigns (WhatsApp / SMS / Telegram / Call / Multi); contact-list and segment audiences; scheduled dispatch; AI content generation |
| **SMS** | Configure Twilio SMS numbers; one-off send; broadcast via campaigns; inbound webhook stores conversation and message |
| **WhatsApp** | Configure Twilio WhatsApp channels; one-off send with optional media URL; inbound webhook |
| **Telegram** | Configure bot tokens; send messages; inbound webhook (POST `/api/titanreach/webhooks/telegram/inbound`) |
| **Call Campaigns** | Twilio Voice call campaigns; TwiML script delivery; status callbacks; call log |
| **Contacts** | Full CRUD with opt-out flag, WhatsApp number, Telegram chat ID |
| **Contact Lists** | Named lists with contacts M:N pivot; used as campaign audience |
| **Segments** | Rule-based contact segments (simple company scoped, easily extendable) |
| **AI Training** | `reach_campaign_embeddings` table stores URL/PDF/text/Q&A training sources for AI reply context |

## Database Tables (12)

| Table | Purpose |
|-------|---------|
| `reach_contacts` | Contacts with phone, email, whatsapp_number, telegram_chat_id, opted_out |
| `reach_contact_lists` | Named contact lists |
| `reach_contact_list_pivot` | M:N pivot between contacts and lists |
| `reach_segments` | Audience segments |
| `reach_campaigns` | Campaigns (channel, audience, content, scheduled_at, status) |
| `reach_campaign_embeddings` | AI training sources (url/pdf/text/qa) |
| `reach_conversations` | Active inbox threads (channel, status, assigned_to, unread_count) |
| `reach_messages` | Message history (direction inbound/outbound, sent/delivered/read timestamps) |
| `reach_whatsapp_channels` | Twilio WhatsApp credentials per company |
| `reach_telegram_bots` | Telegram bot tokens per company |
| `reach_sms_numbers` | Twilio SMS numbers per company |
| `reach_call_campaigns` | Call-only campaign details |
| `reach_call_logs` | Per-call outcome logs |

## Services

| Service | Responsibility |
|---------|---------------|
| `InboxAggregatorService` | Paginated conversation fetch with filters; mark-read; assign; status-update |
| `CampaignDispatchService` | Resolves audience contacts from list/segment; dispatches per-channel |
| `TwilioSmsService` | Twilio REST API for SMS send/broadcast; `receiveInbound()` stores conversation + message |
| `TwilioWhatsappService` | Twilio WhatsApp send; `receiveInbound()` stores conversation + message |
| `TwilioVoiceService` | Outbound call initiation; TwiML generation; status callback handling |
| `TelegramService` | Telegram Bot API send; `receiveInbound()` stores conversation + message |
| `ReachAiService` | Content generation + reply suggestion; routes through `TitanZero::ZeroGateway` when present, or configured HTTP endpoint |

## Webhook Endpoints

| URL | Method | Purpose |
|-----|--------|---------|
| `/api/titanreach/webhooks/sms/inbound` | POST | Twilio inbound SMS |
| `/api/titanreach/webhooks/voice/inbound` | POST | Twilio inbound call |
| `/api/titanreach/webhooks/voice/status` | POST | Twilio call status callback |
| `/api/titanreach/webhooks/voice/twiml` | GET/POST | TwiML response for voice campaigns |
| `/api/titanreach/webhooks/whatsapp/inbound` | POST | Twilio WhatsApp inbound |
| `/api/titanreach/webhooks/telegram/inbound` | POST | Telegram webhook |

Webhooks use the `api` middleware only (no CSRF, no auth). Configure these URLs in the respective
provider dashboards.

## AI Integration

`ReachAiService` checks for `Modules\TitanZero\Services\ZeroGateway` at runtime.
If present it calls `ZeroGateway::ask()`. Otherwise it falls back to the
`TITANREACH_AI_GATEWAY_ENDPOINT` / `TITANREACH_AI_ENABLED` env variables
for a custom HTTP gateway.

## Config Keys (`config/titanreach.php`)

```php
// config/titanreach.php (auto-merged by TitanReachServiceProvider)
return [
    'twilio' => [
        'account_sid'     => env('TWILIO_ACCOUNT_SID'),
        'auth_token'      => env('TWILIO_AUTH_TOKEN'),
        'from_sms_number' => env('TWILIO_SMS_FROM'),
        'from_voice'      => env('TWILIO_VOICE_FROM'),
        'base_url'        => env('TWILIO_BASE_URL', 'https://api.twilio.com/2010-04-01/Accounts'),
    ],
    'telegram' => [
        'default_bot_token' => env('TELEGRAM_BOT_TOKEN'),
    ],
    'ai' => [
        'enabled'          => env('TITANREACH_AI_ENABLED', false),
        'gateway_endpoint' => env('TITANREACH_AI_GATEWAY_ENDPOINT'),
    ],
];
```

## Routes

All web routes are prefixed `account/titanreach` and require `auth` + `web` middleware.
Named routes follow the pattern `titanreach.<resource>.<action>`.

## Sidebar Nav

Registered in `resources/views/sections/menu.blade.php` under a "TitanReach" menu item,
`class_exists`-guarded on `TitanReachServiceProvider`.  Only shown to admin users.

---

### Pass 1 (2026-04-13) — Complete Views + Sidebar + Docs

1. **Missing views added** ✅:
   - `inbox/show.blade.php` — full conversation thread, AI suggest reply (JS fetch), assign/status forms, contact sidebar
   - `whatsapp/index.blade.php` + `whatsapp/create.blade.php`
   - `telegram/index.blade.php` + `telegram/create.blade.php`
   - `sms/create.blade.php`
   - `calls/create.blade.php` (create + edit, `isset($campaign)` pattern)
   - `lists/index.blade.php` + `lists/create.blade.php` + `lists/show.blade.php`

2. **Sidebar nav** ✅ — 11-item TitanReach menu block added to `resources/views/sections/menu.blade.php`.

3. **Documentation** ✅ — This file.
