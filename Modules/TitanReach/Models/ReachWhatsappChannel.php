<?php

namespace Modules\TitanReach\Models;

use Illuminate\Database\Eloquent\Model;

class ReachWhatsappChannel extends Model
{
    protected $table = 'reach_whatsapp_channels';

    protected $fillable = [
        'company_id', 'name', 'account_sid', 'auth_token', 'from_number', 'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    protected $hidden = ['auth_token'];
}
