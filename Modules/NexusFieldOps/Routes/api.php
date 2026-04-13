<?php

use Illuminate\Support\Facades\Route;
use Modules\NexusFieldOps\Http\Controllers\NexusChecklistController;
use Modules\NexusFieldOps\Http\Controllers\NexusDashboardController;
use Modules\NexusFieldOps\Http\Controllers\NexusJobController;
use Modules\NexusFieldOps\Http\Controllers\NexusSyncController;
use Modules\NexusFieldOps\Http\Controllers\NexusWorkerController;

/*
|--------------------------------------------------------------------------
| Titan Go — Nexus Field Ops API  (v1)
|--------------------------------------------------------------------------
| Base prefix: /api/nexus/v1
|
| All routes require a valid Sanctum personal access token:
|   Authorization: Bearer <token>
|
| Tenant context is derived from the authenticated worker's company_id.
| The app MUST also send:
|   X-Company-ID: <company_id>     (validated against token owner)
|   X-Device-ID:  <device_uuid>    (used for deduplication / ping)
|
| Authentication is shared with FSMCore: workers log in via
|   POST /api/fsm/v1/auth/login
| and use the returned token for both /api/fsm/v1/* and /api/nexus/v1/* endpoints.
|
| Endpoints:
|
|   GET  /api/nexus/v1/dashboard                — Today screen payload
|
|   Worker
|   GET  /api/nexus/v1/worker/me                — Worker profile
|   PUT  /api/nexus/v1/worker/fcm-token         — Register/update FCM push token
|   POST /api/nexus/v1/worker/status            — Quick-action signal
|   POST /api/nexus/v1/worker/location          — GPS heartbeat ping
|
|   Jobs (extends FSMCore /api/fsm/v1/orders/*)
|   GET  /api/nexus/v1/jobs/{id}/notes          — List job notes
|   POST /api/nexus/v1/jobs/{id}/notes          — Add worker note
|   GET  /api/nexus/v1/jobs/{id}/issues         — List job issues
|   POST /api/nexus/v1/jobs/{id}/issues         — Report structured issue
|   GET  /api/nexus/v1/jobs/{id}/checklist      — Checklist items (Inspection module)
|   POST /api/nexus/v1/jobs/{id}/checklist      — Mark checklist items complete
|
|   Offline sync
|   POST /api/nexus/v1/sync                     — Replay offline operation queue
*/

Route::prefix('api/nexus/v1')
    ->middleware(['api', 'auth:sanctum'])
    ->group(function () {

        // Dashboard (Today screen)
        Route::get('dashboard', [NexusDashboardController::class, 'index'])
            ->name('nexus.api.dashboard');

        // Worker self-service
        Route::prefix('worker')->group(function () {
            Route::get('me',          [NexusWorkerController::class, 'me'])
                ->name('nexus.api.worker.me');
            Route::put('fcm-token',   [NexusWorkerController::class, 'updateFcmToken'])
                ->name('nexus.api.worker.fcm-token');
            Route::post('status',     [NexusWorkerController::class, 'quickStatus'])
                ->name('nexus.api.worker.status');
            Route::post('location',   [NexusWorkerController::class, 'locationPing'])
                ->name('nexus.api.worker.location');
        });

        // Job extensions (notes, issues, checklists)
        Route::prefix('jobs/{id}')->group(function () {
            Route::get('notes',       [NexusJobController::class, 'noteIndex'])
                ->name('nexus.api.jobs.notes.index');
            Route::post('notes',      [NexusJobController::class, 'noteStore'])
                ->name('nexus.api.jobs.notes.store');
            Route::get('issues',      [NexusJobController::class, 'issueIndex'])
                ->name('nexus.api.jobs.issues.index');
            Route::post('issues',     [NexusJobController::class, 'issueStore'])
                ->name('nexus.api.jobs.issues.store');
            Route::get('checklist',   [NexusChecklistController::class, 'index'])
                ->name('nexus.api.jobs.checklist.index');
            Route::post('checklist',  [NexusChecklistController::class, 'complete'])
                ->name('nexus.api.jobs.checklist.complete');
        });

        // Offline sync queue replay
        Route::post('sync', [NexusSyncController::class, 'replay'])
            ->name('nexus.api.sync');
    });
