<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMSize\Http\Controllers\FsmSizeController;

Route::middleware(['web', 'auth'])->prefix('account/fsm-sizes')->name('fsmsize.')->group(function () {
    Route::get('/', [FsmSizeController::class, 'index'])->name('index');
    Route::get('/create', [FsmSizeController::class, 'create'])->name('create');
    Route::post('/', [FsmSizeController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [FsmSizeController::class, 'edit'])->name('edit');
    Route::post('/{id}', [FsmSizeController::class, 'update'])->name('update');
    Route::post('/{id}/delete', [FsmSizeController::class, 'destroy'])->name('destroy');
});
