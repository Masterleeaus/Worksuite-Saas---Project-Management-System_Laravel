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
- Filters every `getEloquentQuery()` call by `company_id = auth()->user()->company_id`
- Provides `BaseTenantResource::stampTenantData($data)` for Create pages

**Important:** `mutateFormDataBeforeCreate()` is a method on `CreateRecord` pages in
Filament v3, NOT on the Resource class.  To stamp tenant data on creation, override
it in the resource's inner `Pages\CreateMyModel` class:

```php
use App\Filament\Resources\BaseTenantResource;

protected function mutateFormDataBeforeCreate(array $data): array
{
    return BaseTenantResource::stampTenantData($data);
}
```

Tenant middleware: `App\Http\Middleware\ApplyTitanTenantScope` — registered as a
persistent auth middleware in `TitanPanelProvider`. It aborts with 403 if the
authenticated user has no associated company.

---

## 4. Routes

Titan routes live in `routes/Titan/`. They are loaded **recursively** by
`App\Providers\RouteServiceProvider::mapTitanRoutes()`.  Sub-directories such as
`routes/Titan/Api/` are automatically picked up.

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

**Every page must define a `canAccess()` gate.** The panel does NOT limit access by
role by default — any authenticated Worksuite user can reach `/titan` unless gated.

Minimum recommended implementation:

```php
use Filament\Pages\Page;

class MyPage extends Page
{
    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole(['admin', 'superadmin']) ?? false;
    }
}
```

Integrate with Spatie permissions if present:

```php
public static function canAccess(): bool
{
    return auth()->user()?->can('access_titan_panel') ?? false;
}
```

Do **not** create a new permission system — wire into the existing Worksuite gates.

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
