<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMProject\Http\Controllers\EstimateToOrderController;
use Modules\FSMProject\Http\Controllers\FsmProjectController;

Route::middleware(['web', 'auth'])->prefix('account/fsm-project')->name('fsmproject.')->group(function () {
    Route::get('/', [FsmProjectController::class, 'index'])->name('index');
    Route::get('/create', [FsmProjectController::class, 'create'])->name('create');
    Route::post('/', [FsmProjectController::class, 'store'])->name('store');
    Route::get('/{orderId}/edit', [FsmProjectController::class, 'edit'])->name('edit');
    Route::post('/{orderId}', [FsmProjectController::class, 'update'])->name('update');
    Route::post('/{orderId}/unlink', [FsmProjectController::class, 'unlink'])->name('unlink');
    Route::post('/{orderId}/delete', [FsmProjectController::class, 'destroy'])->name('destroy');
});

Route::middleware(['web', 'auth'])->group(function () {
    Route::post('/account/fsm/estimates/{estimateId}/convert-to-order', [EstimateToOrderController::class, 'convert'])
        ->name('fsmproject.estimates.convert-to-order');
});
