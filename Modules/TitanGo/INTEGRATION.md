# Titan Go — Integration Report

## Overview

Titan Go is the canonical field-worker execution client for the Worksuite FSM platform.
It replaces and extends the Nexus Field Ops source application, adding today-first
workflow, offline sync, continuous GPS tracking, proof-of-service lifecycle, and
checklist execution on top of the existing FSMCore and NexusFieldOps API surface.

---

## Source → Destination Mapping

| Nexus Field Ops Source                       | Titan Go Destination                              |
|----------------------------------------------|---------------------------------------------------|
| `src/App.tsx`                                | `Resources/js/src/App.tsx` (rewritten)            |
| `src/types/index.ts`                         | `Resources/js/src/types/index.ts` (extended)      |
| `src/components/TechView.tsx`                | `src/components/VisitView.tsx` (extended)         |
| `src/components/Sidebar.tsx`                 | Removed — replaced by TodayScreen navigation      |
| `src/components/JobCard.tsx`                 | Merged into TodayScreen visit list                |
| `src/components/StepItem.tsx`                | `src/components/ChecklistView.tsx` (extended)     |
| `src/components/ManagerDashboard.tsx`        | Not included (manager surface is web-based)       |
| `src/services/offlineQueue.service.ts`       | `src/services/sync.service.ts` (extended)         |
| `src/services/cache.service.ts`              | Merged into `src/utils/storage.ts`                |
| `src/services/firebase.service.ts`           | `src/services/notification.service.ts` (extended) |
| `src/hooks/useJobs.ts`                       | `src/hooks/useVisits.ts` (extended)               |
| `src/hooks/useOnlineStatus.ts`               | Reused                                            |
| `src/utils/storage.ts`                       | Reused + renamed keys                             |

---

## Renamed Domain Terminology

| Old Term     | Titan Go Term |
|--------------|---------------|
| provider     | company       |
| serviceman   | worker        |
| booking      | visit         |
| order        | visit         |
| tech         | worker        |
| job          | visit         |

---

## Files Modified / Extended (FSMCore)

- `Modules/FSMCore/Routes/api.php` — **read-only**, all new endpoints added in TitanGo module
- `Modules/FSMCore/Http/Controllers/Api/WorkerAuthController.php` — **reused** (login/logout/me)
- `Modules/FSMCore/Http/Controllers/Api/WorkerOrderController.php` — **reused** (checkin/checkout/complete/stage)
- `Modules/FSMCore/Http/Controllers/Api/WorkerPhotoController.php` — **reused** (photos/signatures)

---

## New Files Created

### Laravel Module — `Modules/TitanGo/`

| File                                                        | Purpose                                        |
|-------------------------------------------------------------|------------------------------------------------|
| `module.json`                                               | Module manifest, requires FSMCore              |
| `Providers/TitanGoServiceProvider.php`                      | Register migrations + config                   |
| `Providers/RouteServiceProvider.php`                        | Load API routes                                |
| `Config/config.php`                                         | Module config                                  |
| `Routes/api.php`                                            | All Nexus/Titan Go API routes                  |
| `Models/TitanGoLocationPing.php`                            | GPS ping records                               |
| `Models/TitanGoIssue.php`                                   | Issue reports                                  |
| `Models/TitanGoSiteNote.php`                                | Site memory notes                              |
| `Models/TitanGoWorkerStatus.php`                            | Quick-action status signals                    |
| `Models/NexusJobNote.php`                                   | Visit notes                                    |
| `Database/Migrations/*_create_titan_go_location_pings_table.php` | GPS pings table                         |
| `Database/Migrations/*_create_titan_go_issues_table.php`   | Issues table                                   |
| `Database/Migrations/*_create_titan_go_site_notes_table.php` | Site memory table                            |
| `Database/Migrations/*_create_titan_go_worker_statuses_table.php` | Worker status table                    |
| `Database/Migrations/*_create_nexus_job_notes_table.php`   | Job notes table (idempotent)                   |
| `Database/Migrations/*_add_titan_go_columns_to_users_table.php` | FCM token + device ID on users          |
| `Http/Controllers/Api/DashboardController.php`              | Today screen data                              |
| `Http/Controllers/Api/JobController.php`                    | Visit list + detail + notes                    |
| `Http/Controllers/Api/WorkerController.php`                 | Worker profile + FCM token                     |
| `Http/Controllers/Api/LocationPingController.php`           | GPS heartbeat                                  |
| `Http/Controllers/Api/IssueController.php`                  | Issue escalation                               |
| `Http/Controllers/Api/SiteMemoryController.php`             | Site memory panel                              |
| `Http/Controllers/Api/WorkerStatusController.php`           | Quick-action signals                           |
| `Http/Controllers/Api/SyncController.php`                   | Offline queue replay                           |

### Frontend — `Resources/js/`

| File                                     | Purpose                                        |
|------------------------------------------|------------------------------------------------|
| `src/types/index.ts`                     | Titan Go type system                           |
| `src/services/api.service.ts`            | Tenant-safe API client (X-Company-ID headers)  |
| `src/services/auth.service.ts`           | Login/logout, session persistence              |
| `src/services/sync.service.ts`           | Offline sync queue + replay                    |
| `src/services/gps.service.ts`            | GPS foreground/background tracking             |
| `src/services/notification.service.ts`  | Push notification + FCM token registration     |
| `src/services/media.service.ts`          | Photo/signature upload pipeline                |
| `src/hooks/useVisits.ts`                 | Today's visits, lifecycle actions              |
| `src/hooks/useAuth.ts`                   | Authentication state                           |
| `src/hooks/useOnlineStatus.ts`           | Network status + sync flush trigger            |
| `src/components/LoginScreen.tsx`         | Worker login screen                            |
| `src/components/TodayScreen.tsx`         | Today-first landing screen                     |
| `src/components/VisitView.tsx`           | Full visit execution + proof-of-service        |
| `src/components/ChecklistView.tsx`       | Checklist template engine                      |
| `src/components/SiteMemoryPanel.tsx`     | Site memory context panel                      |
| `src/components/QuickActions.tsx`        | Worker status quick-action bar                 |
| `src/components/IssueForm.tsx`           | Issue escalation form                          |
| `src/App.tsx`                            | Root app with today-first routing              |
| `src/utils/storage.ts`                   | LocalStorage with titan_go_ prefix             |
| `src/utils/uuid.ts`                      | Minimal UUID generator (no dep)                |

---

## API Endpoints Added by TitanGo Module

### Nexus / Titan Go (prefix: `/api/nexus/v1`)

| Method | Path                          | Controller                | Purpose                          |
|--------|-------------------------------|---------------------------|----------------------------------|
| GET    | `/dashboard`                  | DashboardController       | Today screen data                |
| POST   | `/sync`                       | SyncController            | Offline queue replay             |
| GET    | `/jobs`                       | JobController             | Worker's visits                  |
| GET    | `/jobs/{id}`                  | JobController             | Single visit                     |
| GET    | `/jobs/{id}/notes`            | JobController             | Visit notes                      |
| POST   | `/jobs/{id}/notes`            | JobController             | Add note                         |
| GET    | `/jobs/{id}/issues`           | IssueController           | Visit issues                     |
| POST   | `/jobs/{id}/issues`           | IssueController           | Report issue                     |
| GET    | `/jobs/{id}/site-memory`      | SiteMemoryController      | Site context                     |
| POST   | `/jobs/{id}/site-memory`      | SiteMemoryController      | Add site note                    |
| GET    | `/jobs/{id}/status`           | WorkerStatusController    | Status history                   |
| POST   | `/jobs/{id}/status`           | WorkerStatusController    | Quick-action signal              |
| GET    | `/worker/profile`             | WorkerController          | Worker profile                   |
| PUT    | `/worker/fcm-token`           | WorkerController          | Register FCM token               |

### Extended FSM (prefix: `/api/fsm/v1`)

| Method | Path                | Controller              | Purpose             |
|--------|---------------------|-------------------------|---------------------|
| POST   | `/location/ping`    | LocationPingController  | GPS heartbeat       |

### Reused FSMCore (prefix: `/api/fsm/v1`)

| Path                        | Purpose                        |
|-----------------------------|--------------------------------|
| `POST /auth/login`          | Worker authentication          |
| `POST /auth/logout`         | Logout                         |
| `GET  /auth/me`             | Current worker                 |
| `GET  /orders`              | Assigned orders                |
| `GET  /orders/{id}`         | Order detail                   |
| `POST /orders/{id}/checkin` | Check-in                       |
| `POST /orders/{id}/checkout`| Check-out                      |
| `POST /orders/{id}/complete`| Mark complete                  |
| `POST /orders/{id}/stage`   | Advance stage                  |
| `GET  /orders/{id}/photos`  | Photos list                    |
| `POST /orders/{id}/photos`  | Upload photo/signature         |

---

## Tenant Headers

All Titan Go API requests send:

```
Authorization: Bearer <token>
X-Company-ID:  <company_id>
X-Device-ID:   <device_uuid>
```

Tenant scoping is enforced server-side via `HasCompany` trait on all models.

---

## Pass History

### Pass 1 (2026-04-13) — Initial build
Full module scaffold: 8 API controllers, 5 models, 6 migrations, React/TS/Capacitor frontend
(services, hooks, components, routes, providers).

### Pass 2 (2026-04-13) — Tailwind · Checklist API · SignaturePad · Admin panel

1. **Tailwind CSS** ✅ — `@tailwindcss/vite` v4 plugin wired into `vite.config.ts`; `tailwind.config.ts` created; `index.css` updated to `@import "tailwindcss"`.
2. **Checklist API integration** ✅ — Migration `titan_go_checklist_completions`, `TitanGoChecklistCompletion` model, `ChecklistController` (GET steps + completion state, POST step), `useChecklist` hook with optimistic update + offline queue.  `normaliseVisit` fixed to map `template.checklist` (array of strings).  `checklist_step` replay added to `SyncController`.
3. **Signature capture** ✅ — `SignaturePad.tsx` canvas component (touch + mouse, amber stroke on zinc-900 background, Clear/Confirm actions).  `ChecklistView` updated to use `SignaturePad` for signature-required steps and show captured signature previews.
4. **Admin panel** ✅ — `IssueAdminController`, `WorkerStatusAdminController`, `LocationTrackAdminController` + Blade views extending `fsmcore::layouts.master`.  Routes under `/account/titan-go/` (issues, statuses, tracking).  `TitanGoServiceProvider` now loads the `titango::` view namespace.

### Pass 3 (2026-04-13) — Rich checklist steps · FCM · GPS background · Admin nav · Type fix

1. **Rich checklist step schema** ✅ — Migration `2026_04_13_000008_upgrade_fsm_template_checklist_to_rich_steps` converts existing plain-string arrays in-place.  `TemplateController` now parses both the new dynamic step-builder JSON _and_ plain-line textarea (backward-compat).  `ChecklistController` emits `photo_required`, `signature_required`, `is_required` per step; `completeStep` resolves instruction from rich or plain form.  `useVisits.ts` `normaliseVisit` reads rich step objects.  `FSMCore templates/_form.blade.php` upgraded from a textarea to a dynamic JavaScript step builder with per-step photo/signature/required toggles.

2. **FCM / Capacitor Push Notifications** ✅ — `notification.service.ts` rewritten: lazy-loads `@capacitor/push-notifications` plugin; requests OS permissions; registers with FCM/APNs; listens for `registration`, `registrationError`, `pushNotificationReceived`, and `pushNotificationActionPerformed` events; falls back to browser Notification API on web.  `useAuth.ts` calls `notificationService.init()` after successful login (fire-and-forget, errors swallowed with warning).  `package.json` adds `@capacitor/push-notifications ^7.0.0`.

3. **GPS background mode** ✅ — `gps.service.ts` rewritten: lazy-loads `@capacitor-community/background-geolocation` plugin; uses `addWatcher()` for native position delivery (enables Android foreground-service + iOS background mode via `backgroundMessage`); falls back to `navigator.geolocation` interval on web.  `start()` is now `async`.  `VisitView` updated to `.catch(console.warn)` on check-in/out.  `package.json` adds `@capacitor-community/background-geolocation ^1.2.3`.

4. **Admin navigation** ✅ — Titan Go section added to `resources/views/sections/menu.blade.php`, guarded by `in_array('admin', user_roles()) && class_exists(TitanGoServiceProvider)`.  Links: FSM Dashboard, Orders, Checklist Templates (FSMCore-guarded), TG Issues, TG Worker Signals, TG GPS Tracking.

5. **Type correctness** ✅ — `QueueItemType` union in `types/index.ts` now includes `'checklist_step'`.

---

### Pass 4 (2026-04-13) — Proof-of-Delivery Report

1. **`DeliveryReportController`** ✅ — Three actions: `show` (HTML preview), `download` (dompdf PDF), `email` (sends PDF attachment via `ProofOfDeliveryMail`). Shared `buildReportData()` method loads `FSMOrder` with `template`, `location`, `person`, `stage`; merges all `TitanGoChecklistCompletion` rows (including raw `photo_data` / `signature_data`) into a unified steps collection that supports both rich and plain checklist schemas.

2. **`ProofOfDeliveryMail`** ✅ — `Mailable` with `attachData()` of the pre-rendered PDF binary; email body in `titango::mail.proof_of_delivery`.

3. **Blade views** ✅:
   - `titango::admin.delivery.show` — Bootstrap card preview with toolbar (Download PDF / Email to Customer / Back), completion progress bar, checklist table with inline photo/signature thumbnails, collapsible email form pre-filled with location partner email.
   - `titango::admin.delivery.pdf` — Standalone self-contained HTML (DejaVu Sans / dompdf-safe CSS, no Bootstrap), with header, meta-grid, progress bar, checklist table, evidence images rendered from base64, footer.
   - `titango::mail.proof_of_delivery` — Plain HTML email body.

4. **Routes** ✅ — `GET /account/titan-go/delivery/{visitId}` (preview), `GET /account/titan-go/delivery/{visitId}/pdf` (download), `POST /account/titan-go/delivery/{visitId}/email` (email).

5. **Entry points** ✅:
   - Sidebar: "TG Proof of Delivery" sub-item (→ FSM orders list).
   - `FSMCore orders/show.blade.php`: `📋 Proof of Delivery` button (class_exists-guarded).

---

## Passes Remaining Before MVP-Ready Titan Go

1. **Android build** — Run `npx cap sync android` after `npm run build` in `Resources/js/`; add `google-services.json` for FCM; enable `INTERNET`, `ACCESS_FINE_LOCATION`, and `FOREGROUND_SERVICE` permissions in `AndroidManifest.xml`
2. **E2E testing** — Add integration tests for `SyncController` offline replay scenarios (offline → online transitions)
