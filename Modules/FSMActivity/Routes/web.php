<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMActivity\Http\Controllers\ActivityTypeController;
use Modules\FSMActivity\Http\Controllers\ActivityController;
use Modules\FSMActivity\Http\Controllers\GlobalActivityController;
use Modules\FSMActivity\Http\Controllers\ActivityDashboardController;

Route::middleware(['web', 'auth'])->prefix('account/fsm/activities')->group(function () {
    // Dashboard
    Route::get('/dashboard', [ActivityDashboardController::class, 'index'])->name('fsmactivity.dashboard');

    // Global list
    Route::get('/', [GlobalActivityController::class, 'index'])->name('fsmactivity.global.index');

    // Activity Types (config)
    Route::get('/types', [ActivityTypeController::class, 'index'])->name('fsmactivity.types.index');
    Route::get('/types/create', [ActivityTypeController::class, 'create'])->name('fsmactivity.types.create');
    Route::post('/types', [ActivityTypeController::class, 'store'])->name('fsmactivity.types.store');
    Route::get('/types/{id}/edit', [ActivityTypeController::class, 'edit'])->name('fsmactivity.types.edit');
    Route::post('/types/{id}', [ActivityTypeController::class, 'update'])->name('fsmactivity.types.update');
    Route::post('/types/{id}/delete', [ActivityTypeController::class, 'destroy'])->name('fsmactivity.types.destroy');

    // Activities on an order
    Route::get('/orders/{orderId}', [ActivityController::class, 'index'])->name('fsmactivity.activities.index');
    Route::get('/orders/{orderId}/create', [ActivityController::class, 'create'])->name('fsmactivity.activities.create');
    Route::post('/orders/{orderId}', [ActivityController::class, 'store'])->name('fsmactivity.activities.store');
    Route::get('/orders/{orderId}/{id}/edit', [ActivityController::class, 'edit'])->name('fsmactivity.activities.edit');
    Route::post('/orders/{orderId}/{id}', [ActivityController::class, 'update'])->name('fsmactivity.activities.update');
    Route::post('/orders/{orderId}/{id}/delete', [ActivityController::class, 'destroy'])->name('fsmactivity.activities.destroy');
    Route::post('/orders/{orderId}/{id}/done', [ActivityController::class, 'markDone'])->name('fsmactivity.activities.done');
    Route::post('/orders/{orderId}/{id}/cancel', [ActivityController::class, 'markCancelled'])->name('fsmactivity.activities.cancel');
});
