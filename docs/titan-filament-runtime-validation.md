# Titan Filament Runtime Validation

## Package verification

- `composer.json` includes:
  - `filament/filament: ^3.0`
  - `livewire/livewire: ^3.0`
- `composer.lock` confirms both packages are installed:
  - `filament/filament` (lock entry present)
  - `livewire/livewire` (lock entry present)
- `php artisan titan:filament-check` passes all 18 checks.

## Panel/provider verification

- `App\Providers\TitanPanelProvider::class` is registered in `config/app.php` providers.
- No duplicate Titan panel provider registration was found.
- `TitanFilamentServiceProvider` does not call `Filament::registerPanel()`.
- Titan panel path remains `->path('titan')`.
- Titan panel does **not** call `->login()`.
- Titan panel guard remains `->authGuard('web')`.

## Auth verification

- Titan panel now uses `App\Http\Middleware\FilamentAuthenticate`, which extends Filament auth middleware and explicitly redirects unauthenticated browser requests to `route('login')`.
- Playwright runtime check:
  - Request: `GET /titan`
  - Result URL: `/login` (not `/titan/login`)
  - Screenshot captured: `/tmp/playwright-logs/page-2026-04-14T05-27-12-112Z.png`
- Access gate added:
  - `TitanPanelProvider::canAccess()` allows only authenticated tenant users with:
    - admin/superadmin role **or**
    - `titan_access` permission
  - `EnsureTitanPanelAccess` middleware enforces this gate in panel `authMiddleware`.

## Tenant verification

- `BaseTenantResource` remains the base class enforcing tenant query filtering by `company_id`.
- New production resource (`DocumentTemplateResource`) extends `BaseTenantResource`.
- Create flow stamps tenant metadata via:
  - `BaseTenantResource::stampTenantData()` in `ManageDocumentTemplates::mutateFormDataBeforeCreate()`.

## Route verification

- `RouteServiceProvider::mapTitanRoutes()` is recursive (`RecursiveDirectoryIterator` + `RecursiveIteratorIterator`) and loads all `routes/Titan/**/*.php`.
- Temporary runtime verification performed:
  - Created `routes/Titan/test.php` with route name `titan.route-loader-test`
  - Bootstrapped app and confirmed route auto-registered (`registered`)
  - Removed temporary file afterward
- Bootstrapped route dump confirms Titan routes exist (examples):
  - `titan`
  - `titan/command-centre`
  - `titan/document-templates`
  - `titan/logout`

## Working resource proof

- Added `App\Filament\Resources\DocumentTemplateResource` (tenant-scoped).
- Registered in `TitanPanelProvider->resources([...])`.
- Sidebar placement:
  - Navigation group: `TitanDocs`
  - Label: `Document Templates`
- Supports list + create via `ManageDocumentTemplates`.

## Navigation group integration

- `TitanPanelProvider::getModuleNavigationGroups()` is wired through:
  - `->navigationGroups(array_map(... self::getModuleNavigationGroups()))`
- New resource uses an existing module-derived group (`TitanDocs`).

## Command Centre verification

- `/titan` panel landing remains `CommandCentre`.
- Widget rendering uses `getHeaderWidgets()` + `<x-filament-widgets::widgets ... />` (no blade-level `@livewire()` calls).

## Worksuite collision verification

- Titan check confirms no override/collision for:
  - `/dashboard`
  - `/home`
  - `/admin`
  - `/account/*`
- Titan panel remains isolated under `/titan`.

## Validation commands executed

- `php artisan titan:filament-check`
- `php artisan test tests/Feature/Titan/TitanPanelRuntimeTest.php`
- Runtime route bootstrap verification script (temporary `routes/Titan/test.php`)
- Route introspection script for Titan-prefixed URIs

## Unresolved risks

- This environment is not fully installed (installer banner shown), so end-to-end authenticated UI interaction beyond redirect behavior could not be completed here.
- Existing repository-wide tests still include unrelated DB-dependent failures in this sandbox (MySQL connection unavailable); those are outside Titan runtime changes.
