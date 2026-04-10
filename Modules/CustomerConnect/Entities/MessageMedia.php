<?php

namespace Modules\CustomerConnect\Entities;

use Illuminate\Database\Eloquent\Model;

class MessageMedia extends Model
{
    protected $table = 'customerconnect_message_media';

    protected $guarded = [];

    protected $casts = [
        'meta' => 'array',
    ];
}
