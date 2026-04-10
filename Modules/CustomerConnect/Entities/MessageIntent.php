<?php

namespace Modules\CustomerConnect\Entities;

use Illuminate\Database\Eloquent\Model;

class MessageIntent extends Model
{
    protected $table = 'customerconnect_message_intents';

    protected $guarded = [];

    protected $casts = [
        'payload' => 'array',
    ];
}
