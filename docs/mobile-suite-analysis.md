# Deep Mobile Suite Analysis — Field Worker App Candidates

> **Analysis pass only. No features are implemented in this document.**
> Date: 2026-04-13 | Repository: Worksuite-SaaS / CleanSmartOS

---

## Table of Contents

1. [Mobile Suite Inventory](#1-mobile-suite-inventory)
2. [Nexus Field Ops Feature Audit](#2-nexus-field-ops-feature-audit)
3. [Titan Go Suitability Report (TitanPro Worker Surface)](#3-titan-go-suitability-report)
4. [Titan Command Suitability Report (TitanPro Provider Surface)](#4-titan-command-suitability-report)
5. [Backend Compatibility Matrix](#5-backend-compatibility-matrix)
6. [Feature Compatibility Matrix](#6-feature-compatibility-matrix)
7. [Reusable Component Extraction List](#7-reusable-component-extraction-list)
8. [Architecture Recommendation](#8-architecture-recommendation)
9. [Tenant Boundary Compatibility](#9-tenant-boundary-compatibility)

---

## 1. Mobile Suite Inventory

### 1.1 Source Archives

| Location | Archive | Contents |
|---|---|---|
| `/storage/mobile_apps.zip` | `mobile_apps/TitanPro/` | Flutter Provider + ServiceMan app (Demandium v3.7) |
| `codetouse/` (remote branch) | `nexus-field-ops-main_extracted/` | Nexus Field Ops Flutter worker app |

> **Note on Nexus Field Ops:** The extracted Nexus Field Ops directory referenced in the issue
> (`codetouse/nexus-field-ops-main_extracted/`) is located on a separate branch and was not
> present in the current working clone. Section 2 draws on the existing
> `Modules/NexusFieldOps` backend bridge (routes at `api/nexus/v1`) to reconstruct the
> feature contract. The Nexus Field Ops app itself is a Flutter application; its backend
> integration contract is already documented in `Modules/NexusFieldOps/INTEGRATION.md`.

---

### 1.2 TitanPro App — Structural Inventory

**Technology:** Flutter (Dart), SDK 3.38.9, app version 3.7
**State management:** GetX (`get` package)
**HTTP client:** `http` package via custom `ApiClient` (Bearer token auth)
**App identity in archive:** `package:demandium_provider`

#### 1.2.1 App Surfaces Found

| Surface | Role | Evidence |
|---|---|---|
| Provider surface | Company owner / dispatcher | `appUser = 'Provider App'`, routes prefix `/api/v1/provider/*` |
| Serviceman surface | Field worker (tech/cleaner) | `feature/serviceman/`, routes `/api/v1/provider/serviceman`, backend `ServicemanModule` |
| Customer surface | **Not present** in this archive (separate app per Demandium structure) | — |

#### 1.2.2 Screen Inventory

| Feature Module | Screens / Views |
|---|---|
| Splash | `SplashScreen` |
| Auth | `SignInScreen`, `SignUpScreen` (5-step wizard), `ForgotPasswordScreen` |
| Dashboard | `DashboardScreen` (top cards, recent activity, earnings chart) |
| Booking Requests | `BookingRequestsScreen` (list), pending/accepted/ongoing/completed/cancelled tabs |
| Booking Details | `BookingDetailsScreen`, booking status timeline, sub-booking details |
| Serviceman | `ServicemanListScreen`, `ServicemanDetailsScreen`, `AddNewServicemanScreen` (general + account info tabs) |
| Service Details | `ServiceDetailsScreen`, service FAQ, category/subcategory browse |
| Service Availability | Settings per day/time slot |
| Notifications | `NotificationScreen` (push notification list) |
| Conversation/Chat | `ConversationScreen`, channel list, message thread |
| Reporting | `BookingReportScreen`, `BusinessReportScreen`, `TransactionReportScreen` |
| Profile | Account info, profile info, bank information, business information |
| Subscriptions | Business subscription plan management, subcategory subscriptions |
| Advertisement | Ad creation/edit/list/status |
| Custom Post (Bid module) | Customer post list, bid/decline/withdraw |
| Location/Maps | `PickMapScreen`, address autocomplete, Google Maps integration |
| Language | Language selector |
| Settings | Notification settings, business booking settings |
| Tutorial | Onboarding tutorial screens |
| Support | HTML page viewer for T&C / privacy policy |
| Payment Information | Payment method CRUD |
| Transaction | Wallet transaction list |

#### 1.2.3 Controllers / Services

| Controller | Responsibility |
|---|---|
| `AuthController` | Login, logout, token storage, password reset |
| `SignUpController` | Multi-step provider registration |
| `DashboardController` | Dashboard stats, tab control, earning data |
| `BookingRequestController` | Booking list (paginated), accept/ignore |
| `BookingDetailsController` | Booking detail, status change, photo evidence, OTP |
| `BookingEditController` | Service line item editing, schedule update |
| `InvoiceController` | Invoice PDF generation (WebView) |
| `ServicemanSetupController` | Add/edit serviceman, tab control |
| `ServicemanDetailsController` | Serviceman profile detail |
| `LocationController` | GPS position, Google Maps, polyline |
| `NotificationController` | Push notification list |
| `ConversationController` | Chat channel list and message thread |
| `BookingReportController` | Booking report filtering and stats |
| `SplashController` | App config bootstrap, maintenance mode |

#### 1.2.4 Models / Entities

| Model | Key Fields |
|---|---|
| `BookingDetailsModel` | id, bookingStatus, subcategoryId, servicemanId, serviceLocationLatLng, amount, repeatBooking |
| `ServicemanModel` | id, userId, name, phone, email, serviceZoneIds, isActive |
| `SignupBody` | businessName, email, phone, password, zoneId, identityType, identityImages |
| `DashboardTopCards` | totalBooking, totalEarning, ongoingBookings, totalServiceman |
| `EarningDataModel` | monthly/yearly earning arrays |
| `ZoneModel` | id, name, coordinates |
| `NotificationModel` | id, type, data, readAt |
| `PredictionModel` | placeId, description (autocomplete) |

#### 1.2.5 API Endpoints Used (full list from `AppConstants`)

```
POST  /api/v1/provider/auth/login
POST  /api/v1/provider/auth/registration
GET   /api/v1/provider/config
GET   /api/v1/provider/dashboard
GET   /api/v1/provider/dashboard/earning
GET   /api/v1/provider/serviceman
POST  /api/v1/provider/serviceman
PUT   /api/v1/provider/serviceman/{id}
GET   /api/v1/provider/serviceman/{id}
DELETE/api/v1/provider/serviceman/delete
PUT   /api/v1/provider/serviceman/status/update
POST  /api/v1/provider/booking/assign-serviceman
GET   /api/v1/provider/account/overview
PUT   /api/v1/provider/update/profile
GET   /api/v1/provider/booking
GET   /api/v1/provider/booking/calendar/view
GET   /api/v1/provider/booking/{id}
GET   /api/v1/provider/booking/single/{id}
POST  /api/v1/provider/booking/request-accept
POST  /api/v1/provider/booking/request-ignore
POST  /api/v1/provider/booking/single-repeat-cancel/{id}
POST  /api/v1/provider/booking/status-update
POST  /api/v1/provider/booking/single-repeat-status-update
POST  /api/v1/provider/booking/opt/notification-send
GET   /api/v1/provider/get-bank-details
POST  /api/v1/provider/update-bank-details
GET   /api/v1/provider/category
GET   /api/v1/provider/category/childes
GET   /api/v1/provider/service/data/sub-category-wise
GET   /api/v1/provider/service/{id}
GET   /api/v1/provider/faq
PUT   /api/v1/provider/service/update-subscription
PUT   /api/v1/provider/booking/schedule-update
GET   /api/v1/provider/subscribed/sub-categories
GET   /api/v1/provider/notifications
GET   /api/v1/zones
PUT   /api/v1/provider/update/fcm-token
GET   /api/v1/provider/withdraw
GET   /api/v1/provider/withdraw/methods
POST  /api/v1/provider/chat/create-channel
GET   /api/v1/provider/chat/channel-list
GET   /api/v1/provider/chat/conversation
POST  /api/v1/provider/chat/send-message
GET   /api/v1/provider/service/review
GET   /api/v1/provider/review
POST  /api/v1/provider/review-reply
GET   /api/v1/provider/report/transaction
GET   /api/v1/provider/report/booking
GET   /api/v1/provider/report/business/expense|earning|overview
POST  /api/v1/provider/service-request
POST  /api/v1/provider/booking/change-service-location
GET   /api/v1/provider/available-time-schedule
PUT   /api/v1/provider/available-time-schedule
GET   /api/v1/provider/subscription/package/list
GET   /api/v1/provider/post
POST  /api/v1/provider/post/bid
```

#### 1.2.6 State Management

- **GetX** throughout: `GetxController`, `GetxService`, `Rx` observables
- `SharedPreferences` for local token, language, user settings (no offline job queue)

#### 1.2.7 Navigation

- `GetX` named route navigation via `RouteHelper`
- Bottom navigation bar for main surfaces (dashboard, bookings, serviceman, profile)

#### 1.2.8 Offline Storage

- **None** for job data. `SharedPreferences` only for session/config.
- No SQLite, Hive, or Drift offline queue found.

#### 1.2.9 Push Notifications

- **FCM** via `firebase_messaging` (token stored via `PUT /api/v1/provider/update/fcm-token`)
- `NotificationController` fetches in-app notification list from server
- `AppConstants.topic = 'provider-admin'`

#### 1.2.10 Media Handling

- `image_picker` / `file_picker` packages
- Multi-image identity document upload on sign-up (up to 5 files, ≤5 MB)
- Photo evidence on booking completion (`XFile` list → multipart POST)
- Allowed image types: `png, jpg, jpeg, gif, webp`
- Allowed document types: `pdf, csv, txt, xls, xlsx, doc, docx`
- Default image quality: 80

#### 1.2.11 Maps / Location Tracking

- `geolocator` for current position
- `google_maps_flutter` for `PickMapScreen` and polyline display
- Geocode/autocomplete routed through server proxy (`/api/v1/customer/config/geocode-api`)
- **No real-time worker location broadcast** (single-shot pick only)

#### 1.2.12 Authentication

- Bearer token stored in `SharedPreferences`
- `ApiChecker` handles 401 → clears token → redirects to sign-in
- No token refresh endpoint found (re-login required on expiry)
- OTP-based password reset via `/api/v1/user/forget-password/*`
- Firebase OTP verification supported (`/api/v1/user/verification/firebase-auth-verify`)

---

## 2. Nexus Field Ops Feature Audit

> The Nexus Field Ops app code lives on the `codetouse` branch. The backend bridge module
> `Modules/NexusFieldOps` (routes prefix `api/nexus/v1`, Sanctum-protected) defines its
> full API contract. The feature audit below uses the NexusFieldOps module controllers
> (Dashboard, Job, Worker, Sync) as the canonical source of truth for what the app supports.

### 2.1 Worker Workflow Coverage

| Feature | Status | Classification |
|---|---|---|
| Login / auth | ✅ Sanctum token-based (`api/nexus/v1/auth/*`) | **native in backend** |
| Auth token refresh | ⚠️ No dedicated refresh endpoint observed | **needs adapter** |
| Job list | ✅ `JobController@index` — assigned jobs for worker | **native in backend** |
| Job detail | ✅ `JobController@show` | **native in backend** |
| Check-in / Check-out | ✅ FSMCore `orders/{id}/checkin` + `checkout` backed by `WorkerOrderController` | **native in backend** |
| Before / after photos | ✅ `WorkerPhotoController@store` (FSMCore API) — photo_type discriminator | **native in backend** |
| Signatures | ✅ `WorkerPhotoController@store` with `type=signature` | **native in backend** |
| Checklist completion | ✅ Inspection module (`ScheduleInspectionController`, `InspectionTemplateItemController`) — checklist item status update | **native in backend** |
| Issue reporting | ⚠️ No dedicated issue/exception endpoint in NexusFieldOps or FSMCore APIs | **needs extension** |
| Worker notes | ✅ `NexusJobNote` model + `SyncController` | **native in backend** |
| GPS tracking | ⚠️ `WorkerOrderController` accepts `latitude/longitude` on check-in/out but no continuous broadcast | **needs extension** |
| Offline sync | ⚠️ `SyncController` present in NexusFieldOps for queued note sync; no full job cache | **needs extension** |
| Push notifications | ✅ `users.nexus_fcm_token` column + FCM integration | **native in backend** |
| Dispatcher communication | ⚠️ No messaging surface in `api/nexus/v1`; `ChattingModule` exists but not bridged | **needs adapter** |
| Routing / maps usage | ⚠️ No routing endpoint in Nexus API; location proxy via FSMCore | **needs extension** |

### 2.2 Gap Summary

- **3 features native:** login, job list/detail, check-in/out, photos/signatures, checklists, notes, push notifications (7 native total)
- **4 features need extension:** issue reporting, continuous GPS, full offline sync, routing
- **2 features need adapter:** token refresh, dispatcher messaging
- **0 features missing entirely** (all exist in some form in the backend ecosystem)

---

## 3. Titan Go Suitability Report

> **Candidate evaluated:** TitanPro ServiceMan/Provider surface (Demandium worker-facing flows)

Titan Go is the **field worker (cleaner/technician) execution app**. Requirements: today screen,
route ordering, recurring visits, checklists, proof-of-service, offline-first, minimal friction.

### 3.1 Workflow Compatibility Check

| Requirement | TitanPro (Demandium) | Gap / Mismatch |
|---|---|---|
| Today screen | ❌ No "today" filtered view; booking list requires manual filtering by date | Missing — would need `booking?date=today` filter + dedicated screen |
| Route ordering | ❌ No route optimization or stop sequencing | Missing — no `FSMRoute` integration in app |
| Recurring visit awareness | ⚠️ `subBooking` / `repeat_booking` structures exist in `BookingDetailsController` | Partial — UI for repeat sub-bookings exists but worker context is provider-centric |
| Site access notes | ❌ No site access notes field on booking detail worker view | Missing — `ManagedPremises` module has this data server-side |
| Checklist templates | ❌ No checklist UI in TitanPro | Missing — `Inspection` module handles this server-side; app has no checklist engine |
| Supply reporting | ❌ No supply/item consumption UI | Missing — `FieldItems` module exists server-side |
| Exception reporting | ❌ No exception or issue submission flow | Missing |
| Proof-of-service workflow | ⚠️ Photo evidence upload exists (`pickedPhotoEvidence`, multipart POST) | Partial — photos present, but no formal PoS workflow (signature + checklist + photo in sequence) |
| Offline-first execution | ❌ No offline queue; all API calls are live | Missing — critical gap |
| Quick worker status buttons | ⚠️ Status update exists (`changeBookingStatus`) but designed for provider, not worker | Partial — UX friction high; button context is owner/dispatcher |
| Minimal navigation friction | ⚠️ Multi-tab bottom nav, multiple sub-menus | Partial — UX designed for provider management, not single-worker simplicity |

### 3.2 Titan Go Suitability: **LOW**

TitanPro's Demandium worker surface is built for a **provider admin managing servicemen**, not for
a field worker executing jobs. Key blockers:

1. No today screen / route-ordered job queue
2. No offline-first architecture (deal-breaker for field use)
3. No checklist/inspection UI
4. No proof-of-service workflow
5. UX designed for provider management, not worker execution

---

## 4. Titan Command Suitability Report

> **Candidate evaluated:** TitanPro Provider surface (Demandium provider-admin flows)

Titan Command is the **owner/dispatcher mobile app**. Requirements: live worker map, job assignment,
scheduling, shift visibility, alerts, job monitoring, media review, approvals, messaging, dashboards.

### 4.1 Workflow Compatibility Check

| Requirement | TitanPro Provider Surface | Suitability |
|---|---|---|
| Live worker map | ⚠️ `PickMapScreen` exists (Google Maps) but shows service locations, not live worker positions | **Medium** — map infrastructure present, live worker overlay missing |
| Job assignment | ✅ `servicemanAssignUri` — assign serviceman to booking | **High** |
| Drag/drop scheduling | ❌ No drag/drop UI; `BookingCalenderList` exists (calendar view) but no rescheduling drag | **Low** |
| Worker availability | ✅ `getServiceAvailabilitySettings` / `updateServiceAvailabilitySettings` | **High** |
| Shift visibility | ⚠️ Dashboard shows serviceman list and booking stats; no shift schedule view | **Medium** |
| Alerts / exceptions | ⚠️ Notification list exists; no exception/alert classification | **Medium** |
| Job status monitoring | ✅ Booking status tabs (pending/accepted/ongoing/completed/cancelled) | **High** |
| Media review | ⚠️ Completed service images exist in `BookingDetailsController`; no approval flow | **Medium** |
| Approvals | ❌ No approval workflow for exceptions/media/sub-bookings | **Low** |
| Messaging surfaces | ✅ `ConversationController` — chat channels with customers and servicemen | **High** |
| Operational dashboards | ✅ `DashboardController` — booking stats, earnings, serviceman overview | **High** |

### 4.2 Titan Command Suitability Summary

| Capability Area | Suitability |
|---|---|
| Job visibility & assignment | **High** |
| Worker communication | **High** |
| Reporting & dashboards | **High** |
| Live map / location | **Medium** |
| Scheduling management | **Medium** |
| Alerts & exception flow | **Medium** |
| Approval workflows | **Low** |
| Drag/drop scheduling | **Low** |

**Overall: MEDIUM** — TitanPro's provider surface is a reasonable Titan Command base for monitoring
and communication. Key gaps are live worker location overlay, drag/drop scheduling, and an approval
workflow for exceptions and media.

---

## 5. Backend Compatibility Matrix

Backend modules assessed: `FSMCore`, `ServicemanModule`, `Inspection`, `FSMTimesheet`,
`ZoneManagement`, `FSMAvailability`, `FieldItems`, `TitanPWA`

| Mobile Feature | FSMCore | ServicemanModule | Inspection | FSMTimesheet | ZoneManagement | FSMAvailability | FieldItems | TitanPWA |
|---|---|---|---|---|---|---|---|---|
| Login / auth | ✅ `api/fsm/v1/auth/login` | ✅ `/api/v1/serviceman` auth | — | — | — | — | — | — |
| Job list | ✅ `orders` | ✅ `dashboard` | — | — | — | — | — | — |
| Job detail | ✅ `orders/{id}` | — | — | — | — | — | — | — |
| Check-in / Check-out | ✅ `orders/{id}/checkin|checkout` | — | — | ✅ logs timestamps | — | — | — | — |
| Before/after photos | ✅ `orders/{id}/photos` | — | — | — | — | — | — | — |
| Signatures | ✅ `orders/{id}/photos` (type=signature) | — | — | — | — | — | — | — |
| Checklists | — | — | ✅ `ScheduleInspectionController` | — | — | — | — | — |
| Issue reporting | ❌ | ❌ | ⚠️ partial (inspection replies) | — | — | — | — | — |
| Worker notes | ✅ via NexusFieldOps NexusJobNote | — | — | — | — | — | — | — |
| GPS tracking | ⚠️ lat/lng on check-in only | — | — | — | — | — | — | — |
| Offline sync | — | — | — | — | — | — | — | ✅ service worker + background sync |
| Push notifications | ✅ FCM token on user | ✅ FCM token on serviceman | — | — | — | — | — | ✅ Web Push |
| Dispatcher messaging | — | — | — | — | — | — | — | — |
| Zone / routing | — | — | — | — | ✅ ZoneManagement | — | — | — |
| Availability | — | — | — | — | — | ✅ FSMAvailability | — | — |
| Worker notes on items | — | — | — | — | — | — | ✅ FieldItems scan | — |
| Timesheet / hours | — | — | — | ✅ FSMTimesheet | — | — | — | — |
| Scheduling (PWA) | — | — | — | — | — | — | — | ✅ offline-first scheduling |

**Legend:** ✅ already supported | ⚠️ partially supported | ❌ missing

---

## 6. Feature Compatibility Matrix

| Feature | Nexus App | Demandium Worker (TitanPro) | Titan Go Fit | Titan Command Fit | Backend Support |
|---|---|---|---|---|---|
| Jobs (list/detail) | ✅ native | ✅ booking list/detail | ✅ adapt | ✅ adapt | FSMCore + ServicemanModule |
| Visits (recurring) | ⚠️ partial | ⚠️ sub-bookings | ⚠️ needs work | ✅ calendar view | FSMRecurring module |
| Checklists | ✅ via Inspection bridge | ❌ missing | ❌ needs build | ⚠️ review only | Inspection module |
| Inspections | ✅ Inspection module | ❌ missing | ❌ needs build | ⚠️ review only | Inspection module |
| Proof-of-service | ⚠️ photos + signature | ⚠️ photo evidence only | ⚠️ needs workflow | ✅ media review | FSMCore photos |
| Scheduling | ❌ client-only | ⚠️ calendar view | ❌ needs routing | ✅ assign + reschedule | FSMCore + FSMCalendar |
| GPS tracking | ⚠️ check-in lat/lng | ❌ single-shot pick | ❌ needs extension | ⚠️ no live map | FSMCore (partial) |
| Signatures | ✅ FSMCore photo API | ❌ not implemented | ❌ needs UI | ⚠️ can view | FSMCore photos |
| Media uploads | ✅ before/after photos | ✅ photo evidence | ✅ reuse pipeline | ✅ view/approve | FSMCore photos + storage |
| Offline sync | ⚠️ note queue only | ❌ none | ❌ critical gap | ❌ critical gap | TitanPWA (background sync) |
| Push notifications | ✅ FCM | ✅ FCM | ✅ reuse | ✅ reuse | FCM + users.nexus_fcm_token |
| Dispatcher messaging | ❌ missing | ✅ chat channels | ❌ needs adapter | ✅ native | ChattingModule |
| Routing / maps | ❌ missing | ⚠️ pick-map only | ❌ needs build | ⚠️ needs live overlay | ZoneManagement + FSMRoute |
| Availability | ❌ missing | ✅ time-schedule API | ❌ client-only | ✅ set/view | FSMAvailability |
| Worker notes | ✅ NexusJobNote | ❌ missing | ✅ extend | ❌ view only | NexusFieldOps bridge |
| Supply / item tracking | ❌ missing | ❌ missing | ❌ needs build | ❌ needs build | FieldItems module |
| Timesheet / hours | ❌ missing | ❌ missing | ❌ needs build | ✅ could view | FSMTimesheet |

---

## 7. Reusable Component Extraction List

### 7.1 From TitanPro (Demandium Provider App)

| Component | Type | Reuse In | Notes |
|---|---|---|---|
| `ApiClient` (Bearer token, timeout, response handling) | Infrastructure | Titan Go, Titan Command, Nexus Field Ops | Remove hardcoded Demandium endpoint prefixes |
| `ApiChecker` (401 → logout, error snackbar) | Infrastructure | All | Minimal changes needed |
| `LocationController` (geolocator + Google Maps) | Service | Titan Go, Titan Command | Replace single-shot with continuous tracking for Titan Go |
| `PickMapScreen` + map widget | UI | Titan Command | Job location display |
| `ConversationController` + chat thread UI | Feature | Titan Command | Bridge to `ChattingModule` API |
| `NotificationController` + notification list UI | Feature | All | Re-point to `/api/nexus/v1` or `/api/fsm/v1` |
| Photo evidence capture flow (`XFile` list, `image_picker`) | Feature | Titan Go | Adapt to FSMCore `orders/{id}/photos` endpoint |
| Multi-step auth (OTP, Firebase verify) | Auth | All | OTP endpoint differs between surfaces |
| `DashboardController` stats cards | UI | Titan Command | Restyle to FSM terminology |
| Booking status state machine (`statusTypeList`) | Logic | Titan Go, Titan Command | Map to FSM lifecycle stages |
| Repeat/sub-booking UI widgets | UI | Titan Command | Recurring visit overview |
| Report charts (`booking_report_bar_chart`, `fl_chart`) | UI | Titan Command, Nexus Portal | Earnings/booking analytics |
| FCM token update flow | Service | All | Replace endpoint only |
| File picker pipeline (image + document, validation) | Feature | Titan Go | Reuse for attachment uploads |

### 7.2 From Nexus Field Ops (via backend contract)

| Component | Type | Reuse In | Notes |
|---|---|---|---|
| `SyncController` (queued note sync pattern) | Service | Titan Go | Extend to full job cache |
| `NexusJobNote` model | Data | Titan Go | Worker note submission |
| Sanctum token auth with `nexus_fcm_token` | Auth | Titan Go | Already aligned with Worksuite auth |
| Job lifecycle handler (check-in → photos → checkin→ checkout → complete) | Logic | Titan Go | Backbone of Titan Go workflow |

### 7.3 From TitanPWA (Backend Module)

| Component | Type | Reuse In | Notes |
|---|---|---|---|
| Service Worker (offline cache strategy) | PWA | Titan Portal | Already active in backend |
| Background sync queue | Service | Titan Go (hybrid) | Titan Go can use same queue if running as PWA wrapper |
| Web Push integration | Service | All PWA surfaces | Complement FCM for web-based workers |

---

## 8. Architecture Recommendation

### Recommendation: **Hybrid Merge Approach**

#### Rationale

Neither the Nexus Field Ops app nor the TitanPro Demandium app alone is ready to serve as Titan Go
without significant work. However, combining elements from both is significantly cheaper than
rebuilding from scratch.

**Titan Go** should be built on the **Nexus Field Ops app as its base**, with the following
components pulled from TitanPro:

| What to take from Nexus Field Ops | What to pull from TitanPro | What to build new |
|---|---|---|
| Job lifecycle state machine (check-in → checkout → complete) | `ApiClient` + `ApiChecker` infrastructure | Today screen (filtered job queue by date/route) |
| `SyncController` pattern for offline queue | Photo evidence capture pipeline | Checklist engine UI (backed by Inspection module) |
| `NexusJobNote` for worker notes | FCM token management | Continuous GPS broadcast |
| Sanctum auth (already company-scoped) | `LocationController` (adapt to continuous) | Offline job cache (Hive or Drift) |
| — | Auth OTP flow | Issue/exception submission screen |
| — | Chat/messaging UI → bridge to dispatcher | Supply usage reporting (FieldItems API) |

**Titan Command** should be built on the **TitanPro Provider surface** with these additions:

- Live worker location overlay on map (new — polling or WebSocket)
- Drag/drop calendar rescheduling (new — backed by FSMCore stage API)
- Approval workflow UI for exceptions and media review (new — backed by existing approval patterns in Security/Inspection modules)
- Re-skin to FSM terminology (provider → company, serviceman → worker)

#### Why not "adapt Demandium worker app as Titan Go base"?

The Demandium worker surface (Serviceman sub-screen in TitanPro) is designed for **provider admin
managing workers**, not a field-worker execution flow. It has no offline queue, no checklist engine,
no today screen, and no proof-of-service workflow. Adapting it would require replacing nearly every
feature screen — at that point, rebuilding is cheaper.

#### Why not "extract patterns only and rebuild clean"?

Nexus Field Ops already has the correct backend contract (Sanctum, FSMCore, company_id scope) and
the NexusFieldOps module bridge is already wired in the repository. Throwing away the correct
architecture to rebuild would delay delivery without benefit.

---

## 9. Tenant Boundary Compatibility

### 9.1 Terminology Alignment

The system doctrine replaces Demandium terminology as follows:

| Demandium Term | System Doctrine Term | Status in TitanPro |
|---|---|---|
| `provider` | `company` | ❌ Hardcoded throughout: routes `/api/v1/provider/*`, `AppConstants.appUser = 'Provider App'`, `ServicemanSetupController`, API keys |
| `serviceman` | `worker` | ❌ Hardcoded: `servicemanListUri`, `ServicemanDetailsController`, `add_new_service_man` strings, DB column `serviceman_id` |
| `booking` | `order` / `job` | ⚠️ Context-dependent; backend uses `orders` (FSMCore), app uses `booking` |

### 9.2 Tenant Boundary (`company_id`) Compatibility

| Layer | Status | Notes |
|---|---|---|
| FSMCore API (`api/fsm/v1`) | ✅ Sanctum-scoped to authenticated worker's company | `WorkerOrderController` already enforces company scope |
| ServicemanModule API (`api/v1/provider/serviceman`) | ✅ Scoped via `auth:api` + `actch:provider_app` middleware | Already company-aware |
| NexusFieldOps API (`api/nexus/v1`) | ✅ Sanctum + Worksuite module routing | Already correct |
| TitanPro app `baseUrl` | ⚠️ `'YOUR_BASE_URL_HERE'` — single-tenant assumption | Must be configured per-company in SaaS mode; no subdomain/tenant-ID header logic present |
| `CompanyScope` (Worksuite core) | ✅ Applied on `HasCompany` models when `auth()->hasUser()` | Correct; unauthenticated routes (config, zones) are not scoped — intentional |

### 9.3 Worksuite Module Routing Compatibility

| App Surface | Route Prefix | Compatible Module |
|---|---|---|
| TitanPro Provider | `/api/v1/provider/*` | `ServicemanModule` (existing) |
| TitanPro Serviceman | `/api/v1/serviceman/*` | `ServicemanModule` (existing) |
| Nexus Field Ops | `api/nexus/v1` | `NexusFieldOps` module (existing) |
| Titan Go (proposed) | `api/nexus/v1` (extend) | `NexusFieldOps` + `FSMCore` |
| Titan Command (proposed) | `api/v1/provider/*` (extend) | `ServicemanModule` + new controllers |

### 9.4 FSM Lifecycle State Compatibility

FSMCore defines these order states:

```
accepted → ongoing → completed / canceled
```

TitanPro `BookingDetailsController.statusTypeList` maps exactly:

```dart
["accepted", "ongoing", "completed", "canceled"]
```

✅ **Compatible** — Demandium booking status names align with the FSM lifecycle. No translation
layer needed for state transitions, but the endpoints (`/api/v1/provider/booking/status-update` vs
`/api/fsm/v1/orders/{id}/complete`) must be unified under one surface per app role.

### 9.5 Flagged Mismatches

| # | Mismatch | Severity | Action Required |
|---|---|---|---|
| 1 | `provider` terminology throughout TitanPro codebase | High | Global find/replace during Titan Command fork |
| 2 | `serviceman` terminology throughout TitanPro | High | Rename to `worker` in Titan Command fork |
| 3 | No SaaS `company_id` tenant header in `ApiClient` | High | Add `X-Company-Id` or subdomain routing to `updateHeader()` |
| 4 | `baseUrl` is a single static string | Medium | Replace with dynamic subdomain or per-company config from `/api/v1/provider/config` |
| 5 | `AppConstants.topic = 'provider-admin'` (FCM topic) | Low | Replace with company-scoped FCM topic or individual device tokens |
| 6 | No token refresh flow (re-login on 401) | Medium | Implement silent refresh or Sanctum token rotation |
| 7 | GPS is single-shot (pick map) not continuous broadcast | High | Titan Go requires continuous location stream to FSMCore |

---

*End of analysis. No features have been implemented. This document is a discovery artifact.*
