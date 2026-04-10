<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMRecurring\Http\Controllers\FrequencyController;
use Modules\FSMRecurring\Http\Controllers\FrequencySetController;
use Modules\FSMRecurring\Http\Controllers\RecurringController;

Route::middleware(['web', 'auth'])
    ->prefix('account/fsm/recurring')
    ->group(function () {

        // ── Frequency Rules ──────────────────────────────────────────────────
        Route::get('/frequencies',           [FrequencyController::class, 'index'])->name('fsmrecurring.frequencies.index');
        Route::get('/frequencies/create',    [FrequencyController::class, 'create'])->name('fsmrecurring.frequencies.create');
        Route::post('/frequencies',          [FrequencyController::class, 'store'])->name('fsmrecurring.frequencies.store');
        Route::get('/frequencies/{id}/edit', [FrequencyController::class, 'edit'])->name('fsmrecurring.frequencies.edit');
        Route::put('/frequencies/{id}',      [FrequencyController::class, 'update'])->name('fsmrecurring.frequencies.update');
        Route::delete('/frequencies/{id}',   [FrequencyController::class, 'destroy'])->name('fsmrecurring.frequencies.destroy');

        // ── Frequency Sets ───────────────────────────────────────────────────
        Route::get('/frequency-sets',           [FrequencySetController::class, 'index'])->name('fsmrecurring.frequency-sets.index');
        Route::get('/frequency-sets/create',    [FrequencySetController::class, 'create'])->name('fsmrecurring.frequency-sets.create');
        Route::post('/frequency-sets',          [FrequencySetController::class, 'store'])->name('fsmrecurring.frequency-sets.store');
        Route::get('/frequency-sets/{id}/edit', [FrequencySetController::class, 'edit'])->name('fsmrecurring.frequency-sets.edit');
        Route::put('/frequency-sets/{id}',      [FrequencySetController::class, 'update'])->name('fsmrecurring.frequency-sets.update');
        Route::delete('/frequency-sets/{id}',   [FrequencySetController::class, 'destroy'])->name('fsmrecurring.frequency-sets.destroy');

        // ── Recurring Schedules ──────────────────────────────────────────────
        Route::get('/',                   [RecurringController::class, 'index'])->name('fsmrecurring.recurring.index');
        Route::get('/create',             [RecurringController::class, 'create'])->name('fsmrecurring.recurring.create');
        Route::post('/',                  [RecurringController::class, 'store'])->name('fsmrecurring.recurring.store');
        Route::get('/{id}',               [RecurringController::class, 'show'])->name('fsmrecurring.recurring.show');
        Route::get('/{id}/edit',          [RecurringController::class, 'edit'])->name('fsmrecurring.recurring.edit');
        Route::put('/{id}',               [RecurringController::class, 'update'])->name('fsmrecurring.recurring.update');
        Route::delete('/{id}',            [RecurringController::class, 'destroy'])->name('fsmrecurring.recurring.destroy');

        // ── State transitions ────────────────────────────────────────────────
        Route::post('/{id}/start',    [RecurringController::class, 'start'])->name('fsmrecurring.recurring.start');
        Route::post('/{id}/suspend',  [RecurringController::class, 'suspend'])->name('fsmrecurring.recurring.suspend');
        Route::post('/{id}/resume',   [RecurringController::class, 'resume'])->name('fsmrecurring.recurring.resume');
        Route::post('/{id}/close',    [RecurringController::class, 'close'])->name('fsmrecurring.recurring.close');
        Route::post('/{id}/generate', [RecurringController::class, 'generate'])->name('fsmrecurring.recurring.generate');
    });
