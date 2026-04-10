<?php

namespace Modules\ZoneManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\ZoneManagement\Traits\CompanyScoped;

class CleanerLocation extends Model
{
    use HasFactory;
    use CompanyScoped;

    protected $table = 'cleaner_locations';

    protected $fillable = [
        'company_id',
        'user_id',
        'booking_id',
        'lat',
        'lng',
        'accuracy',
        'speed',
        'heading',
        'recorded_at',
    ];

    protected $casts = [
        'lat'         => 'float',
        'lng'         => 'float',
        'accuracy'    => 'float',
        'speed'       => 'float',
        'heading'     => 'float',
        'recorded_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Return the most recent location row for each user (for the dispatch map).
     */
    public function scopeLatestPerUser($query)
    {
        // MySQL-compatible: join on a sub-query that finds the max id per user
        $sub = static::selectRaw('MAX(id) as max_id')
            ->groupBy('user_id');

        return $query->joinSub($sub, 'latest', function ($join) {
            $join->on('cleaner_locations.id', '=', 'latest.max_id');
        });
    }
}
