<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMRouteAvailability\Http\Controllers\BlackoutGroupController;
use Modules\FSMRouteAvailability\Http\Controllers\BlackoutDayController;
use Modules\FSMRouteAvailability\Http\Controllers\RouteBlackoutController;

Route::middleware(['web', 'auth'])
    ->prefix('account/fsm-route-availability')
    ->group(function () {

        // Blackout Groups
        Route::get('/groups',                   [BlackoutGroupController::class, 'index'])->name('fsmrouteavailability.groups.index');
        Route::get('/groups/create',            [BlackoutGroupController::class, 'create'])->name('fsmrouteavailability.groups.create');
        Route::post('/groups',                  [BlackoutGroupController::class, 'store'])->name('fsmrouteavailability.groups.store');
        Route::get('/groups/{id}/edit',         [BlackoutGroupController::class, 'edit'])->name('fsmrouteavailability.groups.edit');
        Route::post('/groups/{id}',             [BlackoutGroupController::class, 'update'])->name('fsmrouteavailability.groups.update');
        Route::post('/groups/{id}/delete',      [BlackoutGroupController::class, 'destroy'])->name('fsmrouteavailability.groups.destroy');

        // Blackout Days
        Route::get('/days',                     [BlackoutDayController::class, 'index'])->name('fsmrouteavailability.days.index');
        Route::get('/days/create',              [BlackoutDayController::class, 'create'])->name('fsmrouteavailability.days.create');
        Route::post('/days',                    [BlackoutDayController::class, 'store'])->name('fsmrouteavailability.days.store');
        Route::get('/days/{id}/edit',           [BlackoutDayController::class, 'edit'])->name('fsmrouteavailability.days.edit');
        Route::post('/days/{id}',               [BlackoutDayController::class, 'update'])->name('fsmrouteavailability.days.update');
        Route::post('/days/{id}/delete',        [BlackoutDayController::class, 'destroy'])->name('fsmrouteavailability.days.destroy');

        // Route ↔ Blackout Group sync
        Route::post('/routes/{route_id}/blackout-groups/sync', [RouteBlackoutController::class, 'sync'])->name('fsmrouteavailability.routes.blackout_sync');

    });
