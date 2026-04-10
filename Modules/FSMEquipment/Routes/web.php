<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMEquipment\Http\Controllers\RepairOrderController;
use Modules\FSMEquipment\Http\Controllers\RepairOrderTemplateController;
use Modules\FSMEquipment\Http\Controllers\EquipmentWarrantyController;
use Modules\FSMEquipment\Http\Controllers\DowntimeReportController;

Route::middleware(['web', 'auth'])
    ->prefix('account/fsm/repair')
    ->group(function () {

        // ── Repair Orders ─────────────────────────────────────────────────────
        Route::get('/',                      [RepairOrderController::class, 'index'])->name('fsmequipment.repair-orders.index');
        Route::get('/create',                [RepairOrderController::class, 'create'])->name('fsmequipment.repair-orders.create');
        Route::post('/',                     [RepairOrderController::class, 'store'])->name('fsmequipment.repair-orders.store');
        Route::get('/{id}',                  [RepairOrderController::class, 'show'])->name('fsmequipment.repair-orders.show');
        Route::get('/{id}/edit',             [RepairOrderController::class, 'edit'])->name('fsmequipment.repair-orders.edit');
        Route::post('/{id}',                 [RepairOrderController::class, 'update'])->name('fsmequipment.repair-orders.update');
        Route::post('/{id}/delete',          [RepairOrderController::class, 'destroy'])->name('fsmequipment.repair-orders.destroy');

        // ── Repair Templates ──────────────────────────────────────────────────
        Route::get('/templates',             [RepairOrderTemplateController::class, 'index'])->name('fsmequipment.repair-templates.index');
        Route::get('/templates/create',      [RepairOrderTemplateController::class, 'create'])->name('fsmequipment.repair-templates.create');
        Route::post('/templates',            [RepairOrderTemplateController::class, 'store'])->name('fsmequipment.repair-templates.store');
        Route::get('/templates/{id}/edit',   [RepairOrderTemplateController::class, 'edit'])->name('fsmequipment.repair-templates.edit');
        Route::post('/templates/{id}',       [RepairOrderTemplateController::class, 'update'])->name('fsmequipment.repair-templates.update');
        Route::post('/templates/{id}/delete',[RepairOrderTemplateController::class, 'destroy'])->name('fsmequipment.repair-templates.destroy');

        // ── Downtime Report ───────────────────────────────────────────────────
        Route::get('/downtime',              [DowntimeReportController::class, 'index'])->name('fsmequipment.downtime.index');

    });

// ── Equipment Warranties (nested under equipment) ─────────────────────────────
Route::middleware(['web', 'auth'])
    ->prefix('account/fsm/equipment')
    ->group(function () {

        Route::get('/{equipmentId}/warranties',             [EquipmentWarrantyController::class, 'index'])->name('fsmequipment.warranties.index');
        Route::get('/{equipmentId}/warranties/create',      [EquipmentWarrantyController::class, 'create'])->name('fsmequipment.warranties.create');
        Route::post('/{equipmentId}/warranties',            [EquipmentWarrantyController::class, 'store'])->name('fsmequipment.warranties.store');
        Route::get('/{equipmentId}/warranties/{id}/edit',   [EquipmentWarrantyController::class, 'edit'])->name('fsmequipment.warranties.edit');
        Route::post('/{equipmentId}/warranties/{id}',       [EquipmentWarrantyController::class, 'update'])->name('fsmequipment.warranties.update');
        Route::post('/{equipmentId}/warranties/{id}/delete',[EquipmentWarrantyController::class, 'destroy'])->name('fsmequipment.warranties.destroy');

    });
