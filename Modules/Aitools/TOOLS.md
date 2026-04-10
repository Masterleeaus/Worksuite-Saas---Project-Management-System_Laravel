# Aitools Tools (Pass 1)

This module exposes a lightweight, TableTrack-style tool registry.
Tools are registered in `AitoolsServiceProvider`.

## Diagnostics endpoints (tenant-safe)
- `GET /account/aitools/tools` — list registered tools
- `POST /account/aitools/tools/{name}` — run a tool with `{ "args": { ... } }`

## Built-in tools
1. `get_today_summary`
2. `search_clients`
3. `search_jobs`
4. `get_unpaid_invoices`
5. `create_task` (defaults to `dry_run=true`)

---

## Pass 2: Chat endpoints

- `POST /account/aitools/chat`
  - Body: `{ "message": "...", "conversation_id": 123 (optional), "meta": { ... } (optional) }`
  - Returns: `{ success, conversation_id, reply, tools[] }`

- `GET /account/aitools/chat/{conversation_id}`
  - Returns conversation metadata + messages.

- `GET /account/aitools/health`
  - Returns basic diagnostics + tool count.

### Explicit tool calls in chat (MVP)
Use:
`/tool get_unpaid_invoices {}`



## get_business_pulse
- **Description:** Summarize recent business signals and key aggregates for the last N hours.
- **Args:** `{ "hours": 24 }` (1–168)
- **Returns:** `{ window_hours, since, signals[], aggregates{} }`
