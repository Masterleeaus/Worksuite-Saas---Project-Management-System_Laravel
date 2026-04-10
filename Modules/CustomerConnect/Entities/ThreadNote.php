<?php

namespace Modules\CustomerConnect\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\CustomerConnect\Traits\TenantScoped;

class ThreadNote extends Model
{
    use TenantScoped;

    protected $table = 'customerconnect_thread_notes';
    protected $guarded = [];

    protected $casts = [
        'meta' => 'array',
    ];

    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class, 'thread_id');
    }
}
