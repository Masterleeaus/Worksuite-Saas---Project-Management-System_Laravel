<?php

namespace Modules\CustomerConnect\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\CustomerConnect\Traits\CompanyScoped;

class Message extends Model
{
    use CompanyScoped;
    protected $table = 'customerconnect_messages';
    protected $guarded = [];
    protected $casts = [
        'meta_json' => 'array',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class, 'thread_id');
    }
}