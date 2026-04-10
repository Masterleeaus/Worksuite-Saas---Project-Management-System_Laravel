# CustomerConnect — Webhooks

CustomerConnect supports inbound and delivery-status webhooks for supported providers.

## Endpoints
Inbound:
- `POST /webhook/customerconnect/twilio`
- `POST /webhook/customerconnect/vonage`

Status callbacks:
- `POST /webhook/customerconnect/twilio/status`
- `POST /webhook/customerconnect/vonage/status`

## Tenant routing
Inbound requests must resolve a tenant/company using `customerconnect_channel_identities`.

Typical mappings:
- SMS: `inbound_address = +614xxxxxxxx`
- WhatsApp (via Twilio): `inbound_address = whatsapp:+614xxxxxxxx`

## Signature verification
CustomerConnect registers middleware aliases inside the module provider (no Kernel edits required):
- `customerconnect.twilio.sig`
- `customerconnect.vonage.sig`

Environment:
- `CUSTOMERCONNECT_WEBHOOK_VERIFY_TWILIO=true`
- `TWILIO_AUTH_TOKEN=...`
- `CUSTOMERCONNECT_WEBHOOK_VERIFY_VONAGE=false`
- `VONAGE_SIGNATURE_SECRET=...`
