<?php

return [
    /*
    |--------------------------------------------------------------------------
    | FSM Calendar Configuration
    |--------------------------------------------------------------------------
    */

    'name' => 'FSMCalendar',

    // Default calendar view: dayGridMonth | timeGridWeek | timeGridDay | listWeek
    'default_view' => env('FSMCALENDAR_DEFAULT_VIEW', 'timeGridWeek'),

    // Slot duration for timeline views (HH:MM:SS)
    'slot_duration' => env('FSMCALENDAR_SLOT_DURATION', '00:30:00'),

    // Business hours shown on the calendar
    'business_hours_start' => env('FSMCALENDAR_BH_START', '07:00'),
    'business_hours_end'   => env('FSMCALENDAR_BH_END',   '20:00'),

    // Fallback event colour when stage has no colour
    'default_event_color' => '#3788d8',
];
