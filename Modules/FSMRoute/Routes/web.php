<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMRoute\Http\Controllers\RouteController;
use Modules\FSMRoute\Http\Controllers\DayRouteController;
use Modules\FSMRoute\Http\Controllers\AvailabilityController;
use Modules\FSMRoute\Http\Controllers\WorkerStatusController;
use Modules\FSMRoute\Http\Controllers\DispatchMapController;

Route::middleware(['web', 'auth'])
    ->prefix('account/fsm-route')
    ->group(function () {

        // Routes
        Route::get('/routes',              [RouteController::class, 'index'])->name('fsmroute.routes.index');
        Route::get('/routes/create',       [RouteController::class, 'create'])->name('fsmroute.routes.create');
        Route::post('/routes',             [RouteController::class, 'store'])->name('fsmroute.routes.store');
        Route::get('/routes/{id}/edit',    [RouteController::class, 'edit'])->name('fsmroute.routes.edit');
        Route::post('/routes/{id}',        [RouteController::class, 'update'])->name('fsmroute.routes.update');
        Route::post('/routes/{id}/delete', [RouteController::class, 'destroy'])->name('fsmroute.routes.destroy');

        // Day Routes
        Route::get('/day-routes',                   [DayRouteController::class, 'index'])->name('fsmroute.day_routes.index');
        Route::get('/day-routes/board',             [DayRouteController::class, 'board'])->name('fsmroute.day_routes.board');
        Route::get('/day-routes/create',            [DayRouteController::class, 'create'])->name('fsmroute.day_routes.create');
        Route::post('/day-routes',                  [DayRouteController::class, 'store'])->name('fsmroute.day_routes.store');
        Route::get('/day-routes/{id}',              [DayRouteController::class, 'show'])->name('fsmroute.day_routes.show');
        Route::get('/day-routes/{id}/edit',         [DayRouteController::class, 'edit'])->name('fsmroute.day_routes.edit');
        Route::post('/day-routes/{id}',             [DayRouteController::class, 'update'])->name('fsmroute.day_routes.update');
        Route::post('/day-routes/{id}/delete',      [DayRouteController::class, 'destroy'])->name('fsmroute.day_routes.destroy');
        Route::get('/day-routes/{id}/print',        [DayRouteController::class, 'print'])->name('fsmroute.day_routes.print');
        Route::post('/day-routes/{id}/reorder',     [DayRouteController::class, 'reorder'])->name('fsmroute.day_routes.reorder');

        // Worker Availability
        Route::get('/availability',        [AvailabilityController::class, 'index'])->name('fsmroute.availability.index');
        Route::post('/availability',       [AvailabilityController::class, 'store'])->name('fsmroute.availability.store');
        Route::post('/availability/delete',[AvailabilityController::class, 'destroy'])->name('fsmroute.availability.destroy');

        // Live Dispatch Map
        Route::get('/dispatch-map',           [DispatchMapController::class, 'index'])->name('fsmroute.dispatch_map.index');
        Route::get('/dispatch-map/locations', [DispatchMapController::class, 'locations'])->name('fsmroute.dispatch_map.locations');

        // Worker Status — en route, check-in, check-out
        Route::post('/orders/{id}/en-route',  [WorkerStatusController::class, 'enRoute'])->name('fsmroute.orders.enRoute');
        Route::post('/orders/{id}/check-in',  [WorkerStatusController::class, 'checkIn'])->name('fsmroute.orders.checkIn');
        Route::post('/orders/{id}/check-out', [WorkerStatusController::class, 'checkOut'])->name('fsmroute.orders.checkOut');

        // Worker Location Ping (used by mobile workers to broadcast their GPS position)
        Route::post('/worker/location', [WorkerStatusController::class, 'pingLocation'])->name('fsmroute.worker.location');

    });
