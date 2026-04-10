<?php

namespace Modules\CustomerConnect\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\CustomerConnect\Traits\CompanyScoped;

class ThreadRead extends Model
{
    use CompanyScoped;
    protected $table = 'customerconnect_thread_reads';

    protected $fillable = [
        'company_id',
        'thread_id',
        'user_id',
        'last_read_at',
    ];

    protected $casts = [
        'last_read_at' => 'datetime',
    ];

    public function thread()
    {
        return $this->belongsTo(Thread::class, 'thread_id');
    }
}