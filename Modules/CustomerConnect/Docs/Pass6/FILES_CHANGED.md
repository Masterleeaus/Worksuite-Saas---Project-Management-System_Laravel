# Pass 6 — AI & Titan Zero Readiness (Draft-Only)

## Added
- `Config/ai.php`
- Migrations:
  - `customerconnect_message_intents`
  - `customerconnect_ai_suggestions`
  - `customerconnect_message_media`
- Entities:
  - `Entities/MessageIntent.php`
  - `Entities/AiSuggestion.php`
  - `Entities/MessageMedia.php`
- Services:
  - `Services/AI/IntentDetector.php`
  - `Services/AI/AiSuggestionService.php`
- Controller:
  - `Http/Controllers/AiAssistController.php`
- Routes:
  - `customerconnect.inbox.threads.ai.apply`
  - `customerconnect.inbox.threads.ai.dismiss`
- View:
  - `Resources/views/inbox/thread.blade.php` (AI Suggestions block)

## Notes
- No external AI providers are called.
- Suggestions are stored as `draft` and require human Apply/Dismiss.
