<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMEquipmentWarranty\Http\Controllers\WarrantyProfileController;
use Modules\FSMEquipmentWarranty\Http\Controllers\EquipmentWarrantyComputeController;

Route::middleware(['web', 'auth'])
    ->prefix('account/fsm-equipment-warranty')
    ->group(function () {
        Route::get('/profiles',              [WarrantyProfileController::class, 'index'])->name('fsmequipmentwarranty.profiles.index');
        Route::get('/profiles/create',       [WarrantyProfileController::class, 'create'])->name('fsmequipmentwarranty.profiles.create');
        Route::post('/profiles',             [WarrantyProfileController::class, 'store'])->name('fsmequipmentwarranty.profiles.store');
        Route::get('/profiles/{id}/edit',    [WarrantyProfileController::class, 'edit'])->name('fsmequipmentwarranty.profiles.edit');
        Route::post('/profiles/{id}',        [WarrantyProfileController::class, 'update'])->name('fsmequipmentwarranty.profiles.update');
        Route::post('/profiles/{id}/delete', [WarrantyProfileController::class, 'destroy'])->name('fsmequipmentwarranty.profiles.destroy');

        Route::post('/compute-end-date', [EquipmentWarrantyComputeController::class, 'compute'])->name('fsmequipmentwarranty.compute_end_date');
    });
