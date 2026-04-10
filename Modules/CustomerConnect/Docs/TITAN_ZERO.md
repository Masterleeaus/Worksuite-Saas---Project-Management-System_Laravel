# CustomerConnect — Titan Zero Integration (Canonical)

CustomerConnect MUST NOT implement AI logic.

## Rules
- No provider/model calls in CustomerConnect.
- No custom Titan Zero execution endpoints inside CustomerConnect.
- Use Titan Zero UI/partials only.

## UI integration
CustomerConnect pages may include an "Ask Titan Zero" button only if:
- `Route::has('titan.zero.index')` and/or hero routes exist
- `View::exists('titanzero::partials.ask_button')`
- Permission gate: `titanzero.use`

CustomerConnect renders the button using:
```blade
@include('titanzero::partials.ask_button', [...])
```

CustomerConnect never logs AI runs; Titan Zero owns all audit/logging.
