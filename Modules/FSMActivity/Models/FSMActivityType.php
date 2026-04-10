<?php

namespace Modules\FSMActivity\Models;

use Illuminate\Database\Eloquent\Model;

class FSMActivityType extends Model
{
    protected $table = 'fsm_activity_types';

    protected $fillable = [
        'company_id',
        'name',
        'icon',
        'delay_count',
        'delay_unit',
        'default_user_id',
        'summary',
        'active',
    ];

    protected $casts = [
        'active'      => 'boolean',
        'delay_count' => 'integer',
    ];

    public function activities()
    {
        return $this->hasMany(FSMActivity::class, 'activity_type_id');
    }

    public function defaultUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'default_user_id');
    }
}
