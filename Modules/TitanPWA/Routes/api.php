<?php

use Illuminate\Support\Facades\Route;
use Modules\TitanPWA\Http\Controllers\Api\PushSubscriptionController;
use Modules\TitanPWA\Http\Controllers\Api\SyncQueueController;

/*
|--------------------------------------------------------------------------
| TitanPWA API Routes
|--------------------------------------------------------------------------
|
| All routes are prefixed /api (set in service provider) and require auth.
|
*/

Route::prefix('titanpwa')
    ->middleware(['auth:sanctum'])
    ->group(function () {

        /*
        |----------------------------------------------------------------------
        | Push Notifications
        |----------------------------------------------------------------------
        */
        Route::post('/push/subscribe',   [PushSubscriptionController::class, 'store'])
            ->name('titanpwa.api.push.subscribe');

        Route::delete('/push/unsubscribe', [PushSubscriptionController::class, 'destroy'])
            ->name('titanpwa.api.push.unsubscribe');

        Route::get('/push/vapid-key', [PushSubscriptionController::class, 'vapidPublicKey'])
            ->name('titanpwa.api.push.vapid_key');

        /*
        |----------------------------------------------------------------------
        | Background Sync Queue
        |----------------------------------------------------------------------
        */
        Route::post('/sync/queue',    [SyncQueueController::class, 'store'])
            ->name('titanpwa.api.sync.store');

        Route::get('/sync/queue',     [SyncQueueController::class, 'index'])
            ->name('titanpwa.api.sync.index');

        Route::post('/sync/process',  [SyncQueueController::class, 'process'])
            ->name('titanpwa.api.sync.process');

        Route::delete('/sync/queue/{id}', [SyncQueueController::class, 'destroy'])
            ->name('titanpwa.api.sync.destroy');

    });
