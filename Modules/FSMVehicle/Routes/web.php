<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMVehicle\Http\Controllers\VehicleController;
use Modules\FSMVehicle\Http\Controllers\MileageLogController;

Route::middleware(['web', 'auth'])
    ->prefix('account/fsm-vehicle')
    ->group(function () {

        // Vehicle fleet list & report
        Route::get('/vehicles',              [VehicleController::class, 'index'])->name('fsmvehicle.vehicles.index');
        Route::get('/vehicles/report',       [VehicleController::class, 'report'])->name('fsmvehicle.vehicles.report');
        Route::get('/vehicles/create',       [VehicleController::class, 'create'])->name('fsmvehicle.vehicles.create');
        Route::post('/vehicles',             [VehicleController::class, 'store'])->name('fsmvehicle.vehicles.store');
        Route::get('/vehicles/{id}',         [VehicleController::class, 'show'])->name('fsmvehicle.vehicles.show');
        Route::get('/vehicles/{id}/edit',    [VehicleController::class, 'edit'])->name('fsmvehicle.vehicles.edit');
        Route::post('/vehicles/{id}',        [VehicleController::class, 'update'])->name('fsmvehicle.vehicles.update');
        Route::post('/vehicles/{id}/delete', [VehicleController::class, 'destroy'])->name('fsmvehicle.vehicles.destroy');

        // Mileage logs (nested under vehicle)
        Route::post('/vehicles/{vehicleId}/mileage',             [MileageLogController::class, 'store'])->name('fsmvehicle.mileage.store');
        Route::post('/vehicles/{vehicleId}/mileage/{logId}/delete', [MileageLogController::class, 'destroy'])->name('fsmvehicle.mileage.destroy');

    });
