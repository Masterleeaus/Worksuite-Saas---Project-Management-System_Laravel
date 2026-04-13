<?php

use Illuminate\Support\Facades\Route;
use Modules\NexusFieldOps\Http\Controllers\Api\NexusDashboardController;
use Modules\NexusFieldOps\Http\Controllers\Api\NexusJobController;
use Modules\NexusFieldOps\Http\Controllers\Api\NexusWorkerController;
use Modules\NexusFieldOps\Http\Controllers\Api\NexusSyncController;

/*
|--------------------------------------------------------------------------
| Nexus Field Ops — Extended API  (v1)
|--------------------------------------------------------------------------
| Base prefix : /api/nexus/v1
|
| These routes extend the core FSMCore worker API (/api/fsm/v1/*) with the
| four features the Nexus Field Ops Flutter app needs that are not already
| present in FSMCore:
|
|   GET  /api/nexus/v1/dashboard            — worker home screen summary
|   GET  /api/nexus/v1/jobs/{id}/notes      — list notes on a job
|   POST /api/nexus/v1/jobs/{id}/notes      — add a note to a job
|   PUT  /api/nexus/v1/worker/fcm-token     — register FCM push token
|   POST /api/nexus/v1/sync                 — offline batch sync replay
|
| All routes require a valid Sanctum Bearer token.
*/

Route::prefix('api/nexus/v1')
    ->middleware(['api', 'auth:sanctum'])
    ->group(function () {

        // Worker home screen
        Route::get('dashboard', [NexusDashboardController::class, 'index'])
            ->name('nexus.api.dashboard');

        // Job notes
        Route::get('jobs/{id}/notes',  [NexusJobController::class, 'listNotes'])
            ->name('nexus.api.jobs.notes.index');
        Route::post('jobs/{id}/notes', [NexusJobController::class, 'addNote'])
            ->name('nexus.api.jobs.notes.store');

        // FCM push token
        Route::put('worker/fcm-token', [NexusWorkerController::class, 'updateFcmToken'])
            ->name('nexus.api.worker.fcm-token');

        // Offline batch sync
        Route::post('sync', [NexusSyncController::class, 'sync'])
            ->name('nexus.api.sync');
    });
