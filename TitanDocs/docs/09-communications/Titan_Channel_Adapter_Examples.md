# Titan Channel Adapter Examples

Provides concrete examples of channel adapter declarations.

## Example 1 — SMS Adapter

```json
{
  "adapter_key": "sms.twilio",
  "channel": "sms",
  "capabilities": ["send_text", "delivery_receipts"],
  "supports_fallback": true,
  "supports_media": false,
  "retry_mode": "safe_retry",
  "timeout_seconds": 30
}
```

## Example 2 — Email Adapter

```json
{
  "adapter_key": "email.smtp",
  "channel": "email",
  "capabilities": ["send_email", "delivery_events", "open_tracking"],
  "supports_fallback": true,
  "supports_media": true,
  "retry_mode": "retryable",
  "timeout_seconds": 60
}
```

## Example 3 — Voice Adapter

```json
{
  "adapter_key": "voice.twilio",
  "channel": "voice",
  "capabilities": ["outbound_call", "call_status"],
  "supports_fallback": false,
  "supports_media": true,
  "retry_mode": "manual_review",
  "timeout_seconds": 45
}
```

## Required Fields

- adapter_key
- channel
- capabilities
- supports_fallback
- retry_mode
- timeout_seconds
- tenant_scope
- approval_requirement
