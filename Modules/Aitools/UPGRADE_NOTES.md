# Aitools Upgrade (2026-02-23)

This ZIP includes Pass 0-4 extraction merge scaffolding from AICore into Worksuite Aitools.

Highlights:
- Provider registry (`ai_providers`) + model registry (`ai_models`)
- Structured telemetry (`ai_usage_logs`, `ai_request_logs`)
- Prompt library (`ai_prompts`)
- Tools registry (`ai_tools_registry`) + dispatcher

All features are feature-flagged and backward compatible with legacy `ai_tools_settings` fields.

## Added after Pass 4

### Pass 5 (Knowledge Base scaffolding)
- Added KB tables: sources, documents, chunks, collections.

### Pass 6 (RAG v1)
- Added embedding + semantic search service.

### Pass 7 (Prompt Runner)
- Added prompt run logging (`ai_prompt_runs`) and a runtime prompt runner.

### Pass 8 (Tool Pack v1)
- Built-in tools shipped and seeded into registry:
  - `kb_search`
  - `summarise_text`
  - `classify_intent`
  - `extract_json`
  - `rewrite_with_tone`

