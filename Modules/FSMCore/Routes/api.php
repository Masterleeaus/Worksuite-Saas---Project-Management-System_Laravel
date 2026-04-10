<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMCore\Http\Controllers\Api\WorkerAuthController;
use Modules\FSMCore\Http\Controllers\Api\WorkerOrderController;
use Modules\FSMCore\Http\Controllers\Api\WorkerPhotoController;

/*
|--------------------------------------------------------------------------
| FSMCore Mobile Worker REST API  (v1)
|--------------------------------------------------------------------------
| Base prefix: /api/fsm/v1
|
| Field workers authenticate via personal access tokens (Laravel Sanctum).
| After login, include the token as:  Authorization: Bearer <token>
|
| Public routes (no auth required):
|   POST  /api/fsm/v1/auth/login
|
| Authenticated routes (Bearer token required):
|   POST  /api/fsm/v1/auth/logout
|   GET   /api/fsm/v1/auth/me
|
|   GET   /api/fsm/v1/orders                   — list assigned orders
|   GET   /api/fsm/v1/orders/{id}              — order detail
|   POST  /api/fsm/v1/orders/{id}/checkin      — record arrival
|   POST  /api/fsm/v1/orders/{id}/checkout     — record departure
|   POST  /api/fsm/v1/orders/{id}/complete     — mark complete
|   POST  /api/fsm/v1/orders/{id}/stage        — advance to explicit stage
|
|   GET   /api/fsm/v1/orders/{id}/photos       — list photos & signatures
|   POST  /api/fsm/v1/orders/{id}/photos       — upload photo / signature
|   DELETE /api/fsm/v1/orders/{id}/photos/{pid} — delete a photo
*/

Route::prefix('api/fsm/v1')
    ->middleware('api')
    ->group(function () {

        // --- Public: authentication ---
        Route::post('auth/login', [WorkerAuthController::class, 'login'])
            ->name('fsm.api.auth.login');

        // --- Protected: require valid Sanctum token ---
        Route::middleware('auth:sanctum')->group(function () {

            Route::post('auth/logout', [WorkerAuthController::class, 'logout'])
                ->name('fsm.api.auth.logout');

            Route::get('auth/me', [WorkerAuthController::class, 'me'])
                ->name('fsm.api.auth.me');

            // Orders
            Route::get('orders',                  [WorkerOrderController::class, 'index'])
                ->name('fsm.api.orders.index');
            Route::get('orders/{id}',             [WorkerOrderController::class, 'show'])
                ->name('fsm.api.orders.show');
            Route::post('orders/{id}/checkin',    [WorkerOrderController::class, 'checkIn'])
                ->name('fsm.api.orders.checkin');
            Route::post('orders/{id}/checkout',   [WorkerOrderController::class, 'checkOut'])
                ->name('fsm.api.orders.checkout');
            Route::post('orders/{id}/complete',   [WorkerOrderController::class, 'complete'])
                ->name('fsm.api.orders.complete');
            Route::post('orders/{id}/stage',      [WorkerOrderController::class, 'updateStage'])
                ->name('fsm.api.orders.stage');

            // Photos / signatures
            Route::get('orders/{id}/photos',                    [WorkerPhotoController::class, 'index'])
                ->name('fsm.api.photos.index');
            Route::post('orders/{id}/photos',                   [WorkerPhotoController::class, 'store'])
                ->name('fsm.api.photos.store');
            Route::delete('orders/{order_id}/photos/{photo_id}', [WorkerPhotoController::class, 'destroy'])
                ->name('fsm.api.photos.destroy');
        });
    });
