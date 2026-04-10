<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMStock\Http\Controllers\StockCategoryController;
use Modules\FSMStock\Http\Controllers\StockItemController;
use Modules\FSMStock\Http\Controllers\OrderStockLineController;
use Modules\FSMStock\Http\Controllers\StockMoveController;
use Modules\FSMStock\Http\Controllers\LocationEquipmentController;
use Modules\FSMStock\Http\Controllers\EquipmentCheckController;
use Modules\FSMStock\Http\Controllers\StockDashboardController;
use Modules\FSMStock\Http\Controllers\StockReportController;

Route::middleware(['web', 'auth'])
    ->prefix('account/fsm/stock')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [StockDashboardController::class, 'index'])->name('fsmstock.dashboard');

        // Stock Categories
        Route::get('/categories',              [StockCategoryController::class, 'index'])->name('fsmstock.stock-categories.index');
        Route::get('/categories/create',       [StockCategoryController::class, 'create'])->name('fsmstock.stock-categories.create');
        Route::post('/categories',             [StockCategoryController::class, 'store'])->name('fsmstock.stock-categories.store');
        Route::get('/categories/{id}/edit',    [StockCategoryController::class, 'edit'])->name('fsmstock.stock-categories.edit');
        Route::post('/categories/{id}',        [StockCategoryController::class, 'update'])->name('fsmstock.stock-categories.update');
        Route::post('/categories/{id}/delete', [StockCategoryController::class, 'destroy'])->name('fsmstock.stock-categories.destroy');

        // Stock Items (Catalogue)
        Route::get('/items',              [StockItemController::class, 'index'])->name('fsmstock.stock-items.index');
        Route::get('/items/create',       [StockItemController::class, 'create'])->name('fsmstock.stock-items.create');
        Route::post('/items',             [StockItemController::class, 'store'])->name('fsmstock.stock-items.store');
        Route::get('/items/{id}/edit',    [StockItemController::class, 'edit'])->name('fsmstock.stock-items.edit');
        Route::post('/items/{id}',        [StockItemController::class, 'update'])->name('fsmstock.stock-items.update');
        Route::post('/items/{id}/delete', [StockItemController::class, 'destroy'])->name('fsmstock.stock-items.destroy');

        // Stock Moves log
        Route::get('/moves',        [StockMoveController::class, 'index'])->name('fsmstock.stock-moves.index');
        Route::post('/moves',       [StockMoveController::class, 'store'])->name('fsmstock.stock-moves.store');
        Route::get('/moves/export', [StockMoveController::class, 'export'])->name('fsmstock.stock-moves.export');

        // Reports
        Route::get('/reports/reorder', [StockReportController::class, 'reorder'])->name('fsmstock.reports.reorder');
        Route::get('/reports/export',  [StockReportController::class, 'export'])->name('fsmstock.reports.export');
    });

// Order stock lines (nested under orders prefix)
Route::middleware(['web', 'auth'])
    ->prefix('account/fsm/orders')
    ->group(function () {
        Route::get('/{orderId}/stock-lines',     [OrderStockLineController::class, 'index'])->name('fsmstock.order-stock-lines.index');
        Route::post('/{orderId}/stock-lines',    [OrderStockLineController::class, 'store'])->name('fsmstock.order-stock-lines.store');
        Route::post('/stock-lines/{id}',         [OrderStockLineController::class, 'update'])->name('fsmstock.order-stock-lines.update');
        Route::post('/stock-lines/{id}/delete',  [OrderStockLineController::class, 'destroy'])->name('fsmstock.order-stock-lines.destroy');
        Route::post('/stock-lines/{id}/consume', [OrderStockLineController::class, 'consume'])->name('fsmstock.order-stock-lines.consume');
    });

// Location equipment register
Route::middleware(['web', 'auth'])
    ->prefix('account/fsm/locations')
    ->group(function () {
        Route::get('/{locationId}/equipment-register',        [LocationEquipmentController::class, 'index'])->name('fsmstock.location-equipment.index');
        Route::post('/{locationId}/equipment-register',       [LocationEquipmentController::class, 'store'])->name('fsmstock.location-equipment.store');
        Route::post('/equipment-register/{id}/delete',        [LocationEquipmentController::class, 'destroy'])->name('fsmstock.location-equipment.destroy');
        Route::post('/equipment-register/{registerId}/check', [EquipmentCheckController::class, 'store'])->name('fsmstock.equipment-check.store');
    });
