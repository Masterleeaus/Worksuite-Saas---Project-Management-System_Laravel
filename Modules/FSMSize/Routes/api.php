<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMSize\Http\Controllers\FsmSizeController;

Route::middleware('auth:sanctum')->prefix('v1/fsm/sizes')->name('api.fsmsize.')->group(function () {
    Route::get('/', [FsmSizeController::class, 'index'])->name('index');
    Route::post('/', [FsmSizeController::class, 'store'])->name('store');
    Route::put('/{id}', [FsmSizeController::class, 'update'])->name('update');
    Route::delete('/{id}', [FsmSizeController::class, 'destroy'])->name('destroy');
});
