<?php

namespace Modules\ClientPulse\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\FSMCore\Models\FSMOrder;

class JobRating extends Model
{
    protected $table = 'client_pulse_job_ratings';

    protected $fillable = [
        'company_id',
        'fsm_order_id',
        'client_id',
        'cleaner_id',
        'stars',
        'comment',
        'rated_at',
    ];

    protected $casts = [
        'company_id'   => 'integer',
        'fsm_order_id' => 'integer',
        'client_id'    => 'integer',
        'cleaner_id'   => 'integer',
        'stars'        => 'integer',
        'rated_at'     => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(FSMOrder::class, 'fsm_order_id');
    }

    public function client()
    {
        return $this->belongsTo(\App\Models\User::class, 'client_id');
    }

    public function cleaner()
    {
        return $this->belongsTo(\App\Models\User::class, 'cleaner_id');
    }

    /**
     * Compute the aggregate rating for a given cleaner user.
     */
    public static function averageForCleaner(int $cleanerId): float
    {
        return (float) static::where('cleaner_id', $cleanerId)->avg('stars');
    }

    /**
     * Count of ratings for a given cleaner user.
     */
    public static function countForCleaner(int $cleanerId): int
    {
        return (int) static::where('cleaner_id', $cleanerId)->count();
    }
}
