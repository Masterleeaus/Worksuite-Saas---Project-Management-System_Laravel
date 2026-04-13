# Nexus Field Ops — Integration Analysis & Guide

## Overview

**Nexus Field Ops** is a Flutter-based cross-platform (iOS + Android) mobile application
designed for field service workers. It was scanned from
`codetouse/nexus-field-ops-main_extracted/` on 2025-12-19.

This document analyses the app's capabilities against the CleanSmartOS backend and provides
a step-by-step integration guide.

---

## Compatibility Summary

| Rating | Score |
|--------|-------|
| **Overall** | ✅ HIGH |
| Native features (no change needed) | 7 / 11 |
| Extended features (added by this module) | 4 / 11 |
| Gaps requiring separate work | 2 (messaging, route nav) |

---

## Feature Matrix

### ✅ Natively Supported (FSMCore `/api/fsm/v1/*`)

| Feature | Nexus endpoint | Backend endpoint | Notes |
|---------|---------------|-----------------|-------|
| Authentication | `POST /v1/auth/login` | `POST /api/fsm/v1/auth/login` | Sanctum token, 1-year expiry |
| Logout | `POST /v1/auth/logout` | `POST /api/fsm/v1/auth/logout` | Revokes Sanctum token |
| Worker profile | `GET /v1/worker/me` | `GET /api/fsm/v1/auth/me` | Returns User record |
| Job list | `GET /v1/jobs` | `GET /api/fsm/v1/orders` | `?status=open\|complete&limit=N` |
| Job detail | `GET /v1/jobs/{id}` | `GET /api/fsm/v1/orders/{id}` | Includes location, equipment, photos |
| GPS check-in | `POST /v1/jobs/{id}/checkin` | `POST /api/fsm/v1/orders/{id}/checkin` | Body: `{latitude, longitude}` |
| GPS check-out | `POST /v1/jobs/{id}/checkout` | `POST /api/fsm/v1/orders/{id}/checkout` | |
| Photo upload | `POST /v1/jobs/{id}/photos` | `POST /api/fsm/v1/orders/{id}/photos` | Multipart; type=photo\|signature |
| Stage/status update | `PATCH /v1/jobs/{id}/status` | `POST /api/fsm/v1/orders/{id}/stage` | Body: `{stage_id}` |

### 🔌 Extended (This Module — `/api/nexus/v1/*`)

| Feature | Nexus endpoint | Backend endpoint | Notes |
|---------|---------------|-----------------|-------|
| Dashboard summary | `GET /v1/dashboard` | `GET /api/nexus/v1/dashboard` | Today's jobs, completion stats |
| Job notes | `POST /v1/jobs/{id}/notes` | `POST /api/nexus/v1/jobs/{id}/notes` | Free-text note attached to order |
| List job notes | `GET /v1/jobs/{id}/notes` | `GET /api/nexus/v1/jobs/{id}/notes` | |
| FCM device token | `PUT /v1/worker/fcm-token` | `PUT /api/nexus/v1/worker/fcm-token` | Stores in `users.nexus_fcm_token` |
| Offline sync | `POST /v1/sync` | `POST /api/nexus/v1/sync` | Batch status/photo updates |

### ⚠️ Gaps (Not Addressed)

| Feature | Reason |
|---------|--------|
| Real-time dispatcher ↔ worker chat | Requires WebSocket/Pusher channel — separate scope |
| Turn-by-turn route navigation | Handled entirely client-side via Google Maps Flutter SDK |

---

## API Authentication

The Nexus Field Ops app uses **Bearer token authentication** (JWT-style).
The CleanSmartOS backend uses **Laravel Sanctum personal access tokens** which are
functionally identical from the mobile app's perspective.

**Login flow:**
```
POST /api/fsm/v1/auth/login
Content-Type: application/json

{ "email": "worker@example.com", "password": "secret" }

→ 200 OK
{ "token": "1|abc...", "expires_at": "2027-04-13T...", "worker": { "id": 5, ... } }
```

Store the token; send it on every subsequent request as:
```
Authorization: Bearer 1|abc...
```

---

## Endpoint Mapping (Quick Reference)

```
# Authentication
POST   /api/fsm/v1/auth/login          ← Nexus /v1/auth/login
POST   /api/fsm/v1/auth/logout         ← Nexus /v1/auth/logout
GET    /api/fsm/v1/auth/me             ← Nexus /v1/worker/me

# Jobs
GET    /api/fsm/v1/orders              ← Nexus /v1/jobs
GET    /api/fsm/v1/orders/{id}         ← Nexus /v1/jobs/{id}
POST   /api/fsm/v1/orders/{id}/checkin ← Nexus /v1/jobs/{id}/checkin
POST   /api/fsm/v1/orders/{id}/checkout← Nexus /v1/jobs/{id}/checkout
POST   /api/fsm/v1/orders/{id}/complete← Nexus /v1/jobs/{id}/complete
POST   /api/fsm/v1/orders/{id}/stage   ← Nexus /v1/jobs/{id}/status (PATCH)
GET    /api/fsm/v1/orders/{id}/photos  ← Nexus /v1/jobs/{id}/photos (GET)
POST   /api/fsm/v1/orders/{id}/photos  ← Nexus /v1/jobs/{id}/photos (POST)
DELETE /api/fsm/v1/orders/{id}/photos/{p} ← Nexus /v1/jobs/{id}/photos/{p} (DELETE)

# Extended (this module)
GET    /api/nexus/v1/dashboard                  ← Nexus /v1/dashboard
GET    /api/nexus/v1/jobs/{id}/notes            ← Nexus /v1/jobs/{id}/notes (GET)
POST   /api/nexus/v1/jobs/{id}/notes            ← Nexus /v1/jobs/{id}/notes (POST)
PUT    /api/nexus/v1/worker/fcm-token           ← Nexus /v1/worker/fcm-token
POST   /api/nexus/v1/sync                       ← Nexus /v1/sync
```

---

## Data Model Additions

### `nexus_job_notes` table
Stores free-text notes that field workers attach to work orders from the mobile app.

| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| fsm_order_id | unsignedBigInteger | FK → fsm_orders.id |
| user_id | unsignedInteger | FK → users.id (author) |
| body | text | Note content |
| timestamps | | created_at, updated_at |

### `users.nexus_fcm_token` column
Nullable string column added to the `users` table to store the Firebase Cloud
Messaging device token for the worker's current mobile device.

---

## Integration Steps for the Flutter App

1. **Change the base URL** in the Nexus app's `lib/config/api_config.dart` (or equivalent)
   to point at your CleanSmartOS server.

2. **Map endpoints** using the quick-reference table above. Most endpoints are a 1:1 rename.

3. **Auth token format** — Sanctum returns `"token": "1|plaintextpart"`.
   Store and send exactly as-is; no JWT decode is needed.

4. **Pagination** — CleanSmartOS returns Laravel paginator JSON (`data`, `meta.total`,
   `links`). The app's list views should read `response.data` for items.

5. **FCM tokens** — call `PUT /api/nexus/v1/worker/fcm-token` after login and whenever
   `FirebaseMessaging.instance.onTokenRefresh` fires.

6. **Offline sync** — queue failed requests locally (e.g. via `flutter_offline` or
   `dio` interceptors) and replay via `POST /api/nexus/v1/sync` when connectivity returns.

---

## Recommendation

> **Integrate.** The Nexus Field Ops Flutter app is a strong fit for CleanSmartOS as a
> field-worker companion app. 7 of its 11 core API calls map directly to existing
> endpoints with only a URL prefix change (`/v1/` → `/api/fsm/v1/`). The 4 remaining
> features (dashboard summary, job notes, FCM token registration, and offline batch sync)
> are implemented by this `NexusFieldOps` module and require no changes to FSMCore.
>
> The only functionality that would need additional backend investment is real-time
> dispatcher–worker messaging, which is out of scope for the current sprint.
