## Pass 3
- Added Titan link-out panel (UI only) to inspection/schedule screens.
- Added integrations config for Titan Docs + Titan Compliance.
- Added TitanLinkService to guard Route::has() and provide URL fallbacks.

## Pass 5
- Marked Inspection as deprecated compatibility bridge.
- Delegated schedule/template/reply/file/recurring controllers to QualityControl canonical controllers.
- Migrated inspection-run runtime ownership to QualityControl execution service through bridge controller.
- Kept legacy route names/URIs and permissions operational for backward compatibility.
