# Titan Filament Panel – Installation & Developer Guide

## Overview

The **Titan Command Centre** is a Filament v3 panel running at `/titan` as a
*parallel UI layer* alongside the existing Worksuite dashboards.

It does **not** replace or modify:
- `/dashboard`, `/home`, `/admin`, `/account/*`
- `routes/web.php`
- existing controllers, auth guards or module providers
- `igaster/laravel-theme` or any Blade namespaces

---

## 1. Install Filament

```bash
composer require filament/filament "^3.0"
php artisan filament:install --panels
```

If you encounter a Livewire version conflict, upgrade Livewire:

```bash
composer require livewire/livewire "^3.0"
```

Do **not** downgrade any Worksuite components.

---

## 2. Panel Details

| Property         | Value                     |
|------------------|---------------------------|
| Panel ID         | `titan`                   |
| Mount path       | `/titan`                  |
| Auth guard       | `web` (Worksuite users)   |
| Provider         | `App\Providers\TitanPanelProvider` |
| User model       | `App\Models\User`         |

---

## 3. Tenant Isolation

All Filament resources **must extend** `App\Filament\Resources\BaseTenantResource`
instead of `Filament\Resources\Resource`.

`BaseTenantResource` automatically:
- Filters every query by `company_id = auth()->user()->company_id`
- Injects `company_id` and `created_by` on record creation
- Binds the model's policy

Tenant middleware: `App\Http\Middleware\ApplyTitanTenantScope` — registered as a
persistent tenant middleware in `TitanPanelProvider`.

---

## 4. Routes

Titan routes live in `routes/Titan/`. They are loaded by
`App\Providers\RouteServiceProvider::mapTitanRoutes()`.

**Never modify `routes/web.php` for Titan features.**

---

## 5. Module-Aware Navigation

`TitanPanelProvider` auto-scans `Modules/*` and `app/Extensions/*` and registers
a navigation group per detected module. Groups appear in the Filament sidebar once
resources are added under each group.

---

## 6. Widgets & Pages (skeleton)

The following are registered as **skeletons** — logic is pending Titan Zero
implementation:

**Widgets**
- `SystemSignalsWidget`
- `JobsTodayWidget`
- `RevenueWidget`
- `ActivityFeedWidget`
- `TitanChatWidget`

**Pages**
- `CommandCentre` (default landing page)
- `AutomationQueue`
- `ScoutStatus`
- `SentinelApprovals`
- `SignalLogs`

---

## 7. Permissions

Filament respects existing Worksuite roles and policies. If `spatie/laravel-permission`
is present it is used automatically. Otherwise the framework falls back to the
existing Worksuite gate logic.

Do **not** create a new permission system.

---

## 8. Verify Installation

```bash
php artisan titan:filament-check
```

Output example:
```
╔══════════════════════════════════════════╗
║  Titan Filament Installation Check        ║
╚══════════════════════════════════════════╝

  ✓  PASS  Filament package installed
  ✓  PASS  TitanPanelProvider exists
  ✓  PASS  TitanPanelProvider in config/app.php
  ✓  PASS  routes/Titan/filament.routes.php exists
  ✓  PASS  /titan route registered
  ✓  PASS  Worksuite route /dashboard not overridden by Filament
  ...
─────────────────────────────────────────────
All 14 checks passed. Titan panel is ready.
─────────────────────────────────────────────
```

---

## 9. Adding New Resources

```bash
php artisan make:filament-resource MyModel --panel=titan
```

Then update the generated class to extend `BaseTenantResource`:

```php
use App\Filament\Resources\BaseTenantResource;

class MyModelResource extends BaseTenantResource
{
    protected static ?string $model = MyModel::class;
    // ...
}
```

---

## 10. Plugin Registration

Future plugins, custom widgets, or voice/PWA bridges can be registered in
`App\Providers\TitanFilamentServiceProvider` by adding entries to its `$plugins`
array.
