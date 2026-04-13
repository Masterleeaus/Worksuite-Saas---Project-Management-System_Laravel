<?php

use Illuminate\Support\Facades\Route;
use Modules\TitanGo\Http\Controllers\Api\DashboardController;
use Modules\TitanGo\Http\Controllers\Api\JobController;
use Modules\TitanGo\Http\Controllers\Api\WorkerController;
use Modules\TitanGo\Http\Controllers\Api\SyncController;
use Modules\TitanGo\Http\Controllers\Api\LocationPingController;
use Modules\TitanGo\Http\Controllers\Api\IssueController;
use Modules\TitanGo\Http\Controllers\Api\SiteMemoryController;
use Modules\TitanGo\Http\Controllers\Api\WorkerStatusController;

/*
|--------------------------------------------------------------------------
| Titan Go — Field Worker API
|--------------------------------------------------------------------------
|
| All routes require a valid Sanctum bearer token obtained from:
|   POST /api/fsm/v1/auth/login
|
| Tenant headers expected on every request:
|   Authorization: Bearer <token>
|   X-Company-ID:  <company_id>
|   X-Device-ID:   <device_uuid>
|
| Nexus / Titan Go endpoints:
|   GET    /api/nexus/v1/dashboard                     — Today screen data
|   GET    /api/nexus/v1/sync                          — sync status
|   POST   /api/nexus/v1/sync                          — offline queue replay
|   GET    /api/nexus/v1/jobs                          — worker's visits
|   GET    /api/nexus/v1/jobs/{id}                     — visit detail
|   GET    /api/nexus/v1/jobs/{id}/notes               — visit notes
|   POST   /api/nexus/v1/jobs/{id}/notes               — add note
|   GET    /api/nexus/v1/jobs/{id}/issues              — visit issues
|   POST   /api/nexus/v1/jobs/{id}/issues              — report issue
|   GET    /api/nexus/v1/jobs/{id}/site-memory         — site context
|   POST   /api/nexus/v1/jobs/{id}/site-memory         — add site note
|   GET    /api/nexus/v1/jobs/{id}/status              — worker status history
|   POST   /api/nexus/v1/jobs/{id}/status              — quick-action signal
|   GET    /api/nexus/v1/worker/profile                — current worker
|   PUT    /api/nexus/v1/worker/fcm-token              — register FCM token
|
| Extended FSM endpoints added by TitanGo:
|   POST   /api/fsm/v1/location/ping                   — GPS heartbeat
*/

Route::prefix('api/nexus/v1')
    ->middleware(['api', 'auth:sanctum'])
    ->group(function () {

        // Dashboard — Today screen
        Route::get('dashboard', [DashboardController::class, 'today'])
            ->name('titango.api.dashboard');

        // Offline sync replay
        Route::post('sync', [SyncController::class, 'replay'])
            ->name('titango.api.sync.replay');

        // Visits (jobs)
        Route::get('jobs',                        [JobController::class, 'index'])
            ->name('titango.api.jobs.index');
        Route::get('jobs/{id}',                   [JobController::class, 'show'])
            ->name('titango.api.jobs.show');
        Route::get('jobs/{id}/notes',             [JobController::class, 'notes'])
            ->name('titango.api.jobs.notes.index');
        Route::post('jobs/{id}/notes',            [JobController::class, 'storeNote'])
            ->name('titango.api.jobs.notes.store');

        // Issues
        Route::get('jobs/{id}/issues',            [IssueController::class, 'index'])
            ->name('titango.api.issues.index');
        Route::post('jobs/{id}/issues',           [IssueController::class, 'store'])
            ->name('titango.api.issues.store');

        // Site memory
        Route::get('jobs/{id}/site-memory',       [SiteMemoryController::class, 'show'])
            ->name('titango.api.site-memory.show');
        Route::post('jobs/{id}/site-memory',      [SiteMemoryController::class, 'store'])
            ->name('titango.api.site-memory.store');

        // Worker status quick-actions
        Route::get('jobs/{id}/status',            [WorkerStatusController::class, 'index'])
            ->name('titango.api.status.index');
        Route::post('jobs/{id}/status',           [WorkerStatusController::class, 'store'])
            ->name('titango.api.status.store');

        // Worker profile + FCM token
        Route::get('worker/profile',              [WorkerController::class, 'profile'])
            ->name('titango.api.worker.profile');
        Route::put('worker/fcm-token',            [WorkerController::class, 'updateFcmToken'])
            ->name('titango.api.worker.fcm-token');
    });

// GPS location ping — separate prefix to match FSMCore convention
Route::prefix('api/fsm/v1')
    ->middleware(['api', 'auth:sanctum'])
    ->group(function () {
        Route::post('location/ping', [LocationPingController::class, 'store'])
            ->name('titango.api.location.ping');
    });
