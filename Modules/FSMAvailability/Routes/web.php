<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMAvailability\Http\Controllers\AvailabilityRuleController;
use Modules\FSMAvailability\Http\Controllers\AvailabilityExceptionController;
use Modules\FSMAvailability\Http\Controllers\AvailabilityCalendarController;
use Modules\FSMAvailability\Http\Controllers\AvailabilityGridController;
use Modules\FSMAvailability\Http\Controllers\PublicHolidayController;

// ── Worker Availability Rules (working-hour patterns per day) ──────────────────
Route::middleware(['web', 'auth'])
    ->prefix('account/fsm/workers')
    ->group(function () {

        Route::get('/{userId}/availability',                [AvailabilityRuleController::class, 'index'])->name('fsmavailability.rules.index');
        Route::get('/{userId}/availability/create',         [AvailabilityRuleController::class, 'create'])->name('fsmavailability.rules.create');
        Route::post('/{userId}/availability',               [AvailabilityRuleController::class, 'store'])->name('fsmavailability.rules.store');
        Route::get('/{userId}/availability/{id}/edit',      [AvailabilityRuleController::class, 'edit'])->name('fsmavailability.rules.edit');
        Route::post('/{userId}/availability/{id}',          [AvailabilityRuleController::class, 'update'])->name('fsmavailability.rules.update');
        Route::post('/{userId}/availability/{id}/delete',   [AvailabilityRuleController::class, 'destroy'])->name('fsmavailability.rules.destroy');

    });

// ── Availability Exceptions (leave, sick, public holiday, etc.) ───────────────
Route::middleware(['web', 'auth'])
    ->prefix('account/fsm/availability')
    ->group(function () {

        Route::get('/exceptions',                           [AvailabilityExceptionController::class, 'index'])->name('fsmavailability.exceptions.index');
        Route::get('/exceptions/create',                    [AvailabilityExceptionController::class, 'create'])->name('fsmavailability.exceptions.create');
        Route::post('/exceptions',                          [AvailabilityExceptionController::class, 'store'])->name('fsmavailability.exceptions.store');
        Route::get('/exceptions/{id}',                      [AvailabilityExceptionController::class, 'show'])->name('fsmavailability.exceptions.show');
        Route::get('/exceptions/{id}/edit',                 [AvailabilityExceptionController::class, 'edit'])->name('fsmavailability.exceptions.edit');
        Route::post('/exceptions/{id}',                     [AvailabilityExceptionController::class, 'update'])->name('fsmavailability.exceptions.update');
        Route::post('/exceptions/{id}/delete',              [AvailabilityExceptionController::class, 'destroy'])->name('fsmavailability.exceptions.destroy');
        Route::post('/exceptions/{id}/approve',             [AvailabilityExceptionController::class, 'approve'])->name('fsmavailability.exceptions.approve');
        Route::post('/exceptions/{id}/reject',              [AvailabilityExceptionController::class, 'reject'])->name('fsmavailability.exceptions.reject');

        // ── Calendar (per-worker week view) ──────────────────────────────────
        Route::get('/calendar',                             [AvailabilityCalendarController::class, 'index'])->name('fsmavailability.calendar.index');

        // ── Availability Grid (full-team status) ─────────────────────────────
        Route::get('/grid',                                 [AvailabilityGridController::class, 'index'])->name('fsmavailability.grid.index');

        // ── Australian Public Holiday Import ─────────────────────────────────
        Route::get('/holidays',                             [PublicHolidayController::class, 'index'])->name('fsmavailability.holidays.index');
        Route::post('/holidays/import',                     [PublicHolidayController::class, 'import'])->name('fsmavailability.holidays.import');

    });
