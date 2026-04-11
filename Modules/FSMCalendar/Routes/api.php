<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMCalendar\Http\Controllers\CalendarController;

Route::middleware(['api', 'auth:api'])
    ->prefix('api/fsm/calendar')
    ->group(function () {

        // FullCalendar JSON event feed
        Route::get('/events', [CalendarController::class, 'events'])->name('api.fsmcalendar.events');

        // Worker resources for resource-timeline view
        Route::get('/resources', [CalendarController::class, 'resources'])->name('api.fsmcalendar.resources');

        // Drag-and-drop / resize reschedule
        Route::post('/orders/{id}/reschedule', [CalendarController::class, 'reschedule'])->name('api.fsmcalendar.reschedule');

        // Quick-create order from time-slot click
        Route::post('/orders/quick-create', [CalendarController::class, 'quickCreate'])->name('api.fsmcalendar.quick-create');

    });
