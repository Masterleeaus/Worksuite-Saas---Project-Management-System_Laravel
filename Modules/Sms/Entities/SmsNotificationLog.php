<?php

namespace Modules\Sms\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;

class SmsNotificationLog extends BaseModel
{
    use HasCompany;

    protected $table = 'sms_notification_logs';

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
