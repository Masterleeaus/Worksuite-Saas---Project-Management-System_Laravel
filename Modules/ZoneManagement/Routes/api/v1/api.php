<?php

use Illuminate\Support\Facades\Route;
use Modules\ZoneManagement\Http\Controllers\PublicZoneController;
use Modules\ZoneManagement\Http\Controllers\Api\V1\Admin\ZoneController;
use Modules\ZoneManagement\Http\Controllers\Api\V1\CheckInController;
use Modules\ZoneManagement\Http\Controllers\Api\V1\LocationPingController;
use Modules\ZoneManagement\Http\Controllers\Api\V1\RouteController;
use Modules\ZoneManagement\Http\Controllers\Api\V1\DispatchMapController;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:api']], function () {
    Route::group(['prefix' => 'zone'], function () {
        Route::get('/', [ZoneController::class, 'index']);   // index
        Route::post('/', [ZoneController::class, 'store']);  // store
        Route::get('{id}/edit', [ZoneController::class, 'edit']); // edit
        Route::put('{id}', [ZoneController::class, 'update']);    // update
    });
    Route::put('zone/status/update', [ZoneController::class, 'statusUpdate']);
    Route::delete('zone/delete', [ZoneController::class, 'destroy']);

    // --- Dispatch map (admin) ---
    Route::get('gps/dispatch-map', [DispatchMapController::class, 'livePositions']);
    Route::get('gps/nearby-cleaners', [DispatchMapController::class, 'nearbySuggestions']);
});

Route::get('zones', [PublicZoneController::class, 'index']);

// --- GPS / Field-ops routes (authenticated cleaner / serviceman) ---
Route::middleware(['auth:api'])->prefix('v1/gps')->as('gps.')->group(function () {
    // Geofence check (before showing check-in button in PWA)
    Route::post('geofence-check', [CheckInController::class, 'geofenceCheck'])->name('geofence-check');

    // Check-in / check-out
    Route::post('check-in',  [CheckInController::class, 'checkIn'])->name('check-in');
    Route::post('check-out', [CheckInController::class, 'checkOut'])->name('check-out');
    Route::get('check-ins',  [CheckInController::class, 'history'])->name('check-ins');

    // Live-location ping
    Route::post('location-ping',    [LocationPingController::class, 'store'])->name('location-ping');
    Route::get('live-locations',    [LocationPingController::class, 'liveLocations'])->name('live-locations');
    Route::get('location-history',  [LocationPingController::class, 'history'])->name('location-history');

    // Route recording
    Route::post('route-points', [RouteController::class, 'store'])->name('route-points.store');
    Route::get('route-points',  [RouteController::class, 'index'])->name('route-points.index');
});

