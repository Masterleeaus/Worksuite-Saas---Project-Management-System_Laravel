<?php

namespace Modules\TitanReach\Models;

use Illuminate\Database\Eloquent\Model;

class ReachSmsNumber extends Model
{
    protected $table = 'reach_sms_numbers';

    protected $fillable = [
        'company_id', 'name', 'phone_number', 'account_sid', 'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
