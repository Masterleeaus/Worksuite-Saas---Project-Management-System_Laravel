<?php

namespace Modules\CustomerConnect\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\CustomerConnect\Traits\CompanyScoped;

class Contact extends Model
{
    use CompanyScoped;
    protected $table = 'customerconnect_contacts';
    protected $guarded = [];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'sms_verified_at' => 'datetime',
        'whatsapp_verified_at' => 'datetime',
        'telegram_verified_at' => 'datetime',
    ];
}