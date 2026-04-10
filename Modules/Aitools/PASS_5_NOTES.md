# Pass 5 Notes — Pulse & Signals

This pass adds the foundation for **proactive insights** (TableTrack-style) without requiring edits to Worksuite core.

## What's included
- `ai_tools_signals` table + `AiToolsSignal` model
- `ai_tools_pulse_summaries` table + `AiToolsPulseSummary` model
- `PulseService` to build a compact pulse payload (best-effort)
- Tool: `get_business_pulse`
- Command: `php artisan aitools:generate-pulse`

## How to use
### 1) Run migrations
Run your normal Worksuite migration flow so the new tables are created.

### 2) Generate a pulse summary (manual)
Generate for a specific company:

```bash
php artisan aitools:generate-pulse --company_id=1 --window=daily --hours=24
```

Generate for all companies (best-effort; uses `companies` table):

```bash
php artisan aitools:generate-pulse --window=daily --hours=24
```

### 3) Call via tools API
```http
POST /account/aitools/tools/get_business_pulse
{ "args": { "hours": 24 } }
```

## Next recommended hardening (Pass 5.1 / 5.2)
- Wire **signals** from real domain events (Invoices overdue, Jobs cancelled, Payments received).
- Add a scheduler entry (Laravel `Kernel.php`) to run `aitools:generate-pulse` daily.
- Add an "Insights" tab in the floating widget to show daily pulse summaries.
