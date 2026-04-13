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

## Passes Remaining Before MVP-Ready Titan Go

1. **Tailwind CSS** — Add tailwind to `vite.config.ts` (currently CSS references `@tailwind` directives; install `tailwindcss` npm package and configure)
2. **Checklist API integration** — Connect FSMCore template steps to real API via `GET /api/fsm/v1/orders/{id}` template data
3. **Signature capture** — Add canvas-based signature component (replace file input workaround in ChecklistView)
4. **Android build** — Run `npx cap sync android` after `npm run build` in `Resources/js/`
5. **FCM setup** — Configure Firebase project credentials in production env and wire `firebaseFcmToken` into `useAuth`
6. **GPS background mode** — Add Capacitor Background Geolocation plugin for true background tracking on Android/iOS
7. **Proof-of-service sign-off** — Add signature-required step completion in ChecklistView (stub is in place)
8. **Admin panel integration** — Surface `titan_go_issues`, `titan_go_worker_statuses`, and `titan_go_location_pings` in the Worksuite admin UI
9. **Inspection module hook-up** — Wire `FSMCore template.steps` to the Inspection module for checklist template management
10. **E2E testing** — Add integration tests for SyncController replay scenarios (offline → online transitions)
