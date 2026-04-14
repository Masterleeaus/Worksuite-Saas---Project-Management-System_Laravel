<?php

use Illuminate\Support\Facades\Route;
use Modules\TitanIntegrations\Http\Controllers\Api\V1\BookingApiController;
use Modules\TitanIntegrations\Http\Controllers\Api\V1\ClientApiController;
use Modules\TitanIntegrations\Http\Controllers\Api\V1\InvoiceApiController;
use Modules\TitanIntegrations\Http\Controllers\Api\V1\ServiceApiController;
use Modules\TitanIntegrations\Http\Controllers\Api\V1\ZoneApiController;

/*
|--------------------------------------------------------------------------
| TitanIntegrations REST API — external systems call WorkSuite
|--------------------------------------------------------------------------
| Auth: Bearer token (ApiToken model)
| Rate limit: 60 requests/minute per token
| Prefix: /api/v1
*/

// Health check (no auth required)
Route::prefix('v1')->group(function () {
    Route::get('ping', fn() => response()->json(['ok' => true, 'service' => 'WorkSuite API v1']));
});

Route::prefix('v1')
    ->middleware(['throttle:60,1', \Modules\TitanIntegrations\Http\Middleware\ApiTokenMiddleware::class])
    ->group(function () {

        // Bookings (tasks with task_type='booking')
        Route::apiResource('bookings', BookingApiController::class)->names('titan-integrations.bookings');

        // Clients (users with 'client' role)
        Route::get('clients',       [ClientApiController::class, 'index']);
        Route::get('clients/{id}',  [ClientApiController::class, 'show']);
        Route::post('clients',      [ClientApiController::class, 'store']);

        // Invoices
        Route::get('invoices',      [InvoiceApiController::class, 'index']);
        Route::get('invoices/{id}', [InvoiceApiController::class, 'show']);

        // Service catalogue
        Route::get('services',      [ServiceApiController::class, 'index']);

        // Available zones
        Route::get('zones',         [ZoneApiController::class, 'index']);
    });
