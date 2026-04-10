<?php

use Illuminate\Support\Facades\Route;
use Modules\ZoneManagement\Http\Controllers\Web\Admin\ZoneController;
use Modules\ZoneManagement\Http\Controllers\Web\Admin\DispatchMapController;
use Modules\ZoneManagement\Http\Controllers\Web\Admin\GpsSettingsController;
use Modules\ZoneManagement\Http\Controllers\Web\Admin\RouteReplayController;

Route::middleware(['web','auth'])->prefix('account')->group(function () {
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Web\Admin', 'middleware' => ['admin', 'actch:admin_panel']], function () {
    Route::group(['prefix' => 'zone', 'as' => 'zone.'], function () {
        Route::get('/', [ZoneController::class, 'create'])->name('index');
        Route::any('create', [ZoneController::class, 'create'])->name('create');
        Route::post('store', [ZoneController::class, 'store'])->name('store');
        Route::get('edit/{id}', [ZoneController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [ZoneController::class, 'update'])->name('update');
        Route::put('get-active-zones/{id}', [ZoneController::class, 'getActiveZones'])->name('get-active-zones');
        Route::any('status-update/{id}', [ZoneController::class, 'statusUpdate'])->name('status-update');
        Route::delete('delete/{id}', [ZoneController::class, 'destroy'])->name('delete');
        Route::get('download', [ZoneController::class, 'download'])->name('download');
        Route::get('table', [ZoneController::class, 'getTable'])->name('table');
    });

    // GPS / dispatch-map admin pages
    Route::get('gps/dispatch-map', [DispatchMapController::class, 'index'])->name('gps.dispatch-map');
    Route::get('gps/settings', [GpsSettingsController::class, 'index'])->name('gps.settings');
    Route::post('gps/settings', [GpsSettingsController::class, 'update'])->name('gps.settings.update');
    Route::get('gps/route-replay/{booking_id}', [RouteReplayController::class, 'show'])->name('gps.route-replay');
});
});
