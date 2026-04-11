<?php

namespace Modules\SynapseDispatch\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DispatchEvent extends Model
{
    protected $table = 'dispatch_events';

    protected $fillable = [
        'uuid',
        'started_at',
        'ended_at',
        'description',
        'job_id',
        'worker_id',
        'source',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $event) {
            if (empty($event->uuid)) {
                $event->uuid = (string) Str::uuid();
            }
            if (empty($event->started_at)) {
                $event->started_at = now();
            }
        });
    }

    public function job()
    {
        return $this->belongsTo(DispatchJob::class, 'job_id');
    }

    public function worker()
    {
        return $this->belongsTo(DispatchWorker::class, 'worker_id');
    }
}
