<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMSaleStock\Http\Controllers\StockRequisitionController;
use Modules\FSMSaleStock\Http\Controllers\StockRequisitionLineController;

Route::middleware(['web', 'auth'])
    ->prefix('account/fsm-sale-stock')
    ->group(function () {
        Route::get('/',              [StockRequisitionController::class, 'index'])->name('fsmsalestock.index');
        Route::get('/create',        [StockRequisitionController::class, 'create'])->name('fsmsalestock.create');
        Route::post('/',             [StockRequisitionController::class, 'store'])->name('fsmsalestock.store');
        Route::get('/{id}',          [StockRequisitionController::class, 'show'])->name('fsmsalestock.show');
        Route::get('/{id}/edit',     [StockRequisitionController::class, 'edit'])->name('fsmsalestock.edit');
        Route::post('/{id}',         [StockRequisitionController::class, 'update'])->name('fsmsalestock.update');
        Route::post('/{id}/delete',  [StockRequisitionController::class, 'destroy'])->name('fsmsalestock.destroy');
        Route::post('/{id}/submit',  [StockRequisitionController::class, 'submit'])->name('fsmsalestock.submit');
        Route::post('/{id}/fulfill', [StockRequisitionController::class, 'fulfill'])->name('fsmsalestock.fulfill');
        Route::post('/{id}/bill',    [StockRequisitionController::class, 'bill'])->name('fsmsalestock.bill');

        Route::post('/{id}/lines',         [StockRequisitionLineController::class, 'store'])->name('fsmsalestock.lines.store');
        Route::post('/lines/{lineId}/delete', [StockRequisitionLineController::class, 'destroy'])->name('fsmsalestock.lines.destroy');
    });
