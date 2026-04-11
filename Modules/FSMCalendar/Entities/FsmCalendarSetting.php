<?php

namespace Modules\FSMCalendar\Entities;

use Illuminate\Database\Eloquent\Model;

class FsmCalendarSetting extends Model
{
    protected $table = 'fsm_calendar_settings';

    protected $fillable = [
        'company_id',
        'default_view',
        'slot_duration',
        'business_hours_start',
        'business_hours_end',
        'default_event_color',
        'show_resource_view',
        'show_weekends',
    ];

    protected $casts = [
        'show_resource_view' => 'boolean',
        'show_weekends'      => 'boolean',
    ];

    /**
     * Retrieve (or synthesise) settings for a company.
     * Falls back to config values so the calendar works before migration runs.
     */
    public static function forCompany(?int $companyId): array
    {
        if ($companyId && \Illuminate\Support\Facades\Schema::hasTable('fsm_calendar_settings')) {
            $row = static::where('company_id', $companyId)->first();
            if ($row) {
                return $row->toArray();
            }
        }

        // Return config / env defaults
        return [
            'company_id'           => $companyId,
            'default_view'         => config('fsmcalendar.default_view', 'timeGridWeek'),
            'slot_duration'        => config('fsmcalendar.slot_duration', '00:30:00'),
            'business_hours_start' => config('fsmcalendar.business_hours_start', '07:00'),
            'business_hours_end'   => config('fsmcalendar.business_hours_end', '20:00'),
            'default_event_color'  => config('fsmcalendar.default_event_color', '#3788d8'),
            'show_resource_view'   => true,
            'show_weekends'        => false,
        ];
    }
}
