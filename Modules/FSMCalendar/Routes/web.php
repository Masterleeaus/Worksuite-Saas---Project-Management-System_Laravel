<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMCalendar\Http\Controllers\CalendarController;

Route::middleware(['web', 'auth'])
    ->prefix('account/fsm/calendar')
    ->group(function () {

        // Main calendar page
        Route::get('/', [CalendarController::class, 'index'])->name('fsmcalendar.index');

        // FullCalendar JSON event feed
        Route::get('/events', [CalendarController::class, 'events'])->name('fsmcalendar.events');

        // Drag-and-drop / resize reschedule (AJAX POST)
        Route::post('/orders/{id}/reschedule', [CalendarController::class, 'reschedule'])->name('fsmcalendar.reschedule');

        // Quick-create order from time-slot click (AJAX POST)
        Route::post('/orders/quick-create', [CalendarController::class, 'quickCreate'])->name('fsmcalendar.quick-create');

        // Worker resources for FullCalendar resource timeline
        Route::get('/resources', [CalendarController::class, 'resources'])->name('fsmcalendar.resources');

    });
