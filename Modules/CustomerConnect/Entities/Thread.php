<?php

namespace Modules\CustomerConnect\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\CustomerConnect\Traits\CompanyScoped;

class Thread extends Model
{
    use CompanyScoped;
    protected $table = 'customerconnect_threads';
    protected $guarded = [];
    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'thread_id');
    }
}