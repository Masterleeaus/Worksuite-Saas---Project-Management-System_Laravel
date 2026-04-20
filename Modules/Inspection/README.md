# Inspection Module

> Deprecated bridge module. Canonical inspection ownership is in `Modules/QualityControl`.

## Pass 1 changes
- Adds canonical, sidebar-safe route names.
- Guards sidebar links with `Route::has()` to prevent dashboard crashes.

## Ownership (current)
- Keep this module enabled only for legacy route/permission compatibility.
- All canonical business logic for templates, schedules, findings, scoring, and execution records is owned by `QualityControl`.

## Canonical routes
- `recurring-inspection_schedules.*`
- `inspection_schedules.*`
- `schedule-inspection.*`

## Deploy
```bash
cd /home/saassmar/domains/admin.buildsm.art/public_html
php artisan optimize:clear
php artisan route:clear
```
