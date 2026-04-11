<?php

namespace Modules\FSMKanban\Entities;

use Illuminate\Database\Eloquent\Model;

class FsmKanbanSetting extends Model
{
    protected $table = 'fsm_kanban_settings';

    protected $fillable = [
        'company_id', 'show_schedule_range', 'show_worker',
        'show_location', 'show_priority',
    ];

    protected $casts = [
        'show_schedule_range' => 'boolean',
        'show_worker'         => 'boolean',
        'show_location'       => 'boolean',
        'show_priority'       => 'boolean',
    ];
}
