<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMProject\Http\Controllers\FsmProjectController;

Route::middleware('auth:sanctum')->prefix('v1/fsm/project')->name('api.fsmproject.')->group(function () {
    Route::post('/orders/{orderId}/link', [FsmProjectController::class, 'link'])->name('orders.link');
    Route::post('/orders/{orderId}/unlink', [FsmProjectController::class, 'unlink'])->name('orders.unlink');
    Route::get('/projects/{projectId}/orders', [FsmProjectController::class, 'byProject'])->name('projects.orders');
    Route::get('/projects/{projectId}/summary', [FsmProjectController::class, 'summary'])->name('projects.summary');
});
