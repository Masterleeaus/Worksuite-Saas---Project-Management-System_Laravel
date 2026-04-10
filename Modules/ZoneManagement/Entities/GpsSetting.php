<?php

namespace Modules\ZoneManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GpsSetting extends Model
{
    use HasFactory;

    protected $table = 'gps_settings';

    protected $fillable = [
        'company_id',
        'location_ping_interval',
        'poor_accuracy_threshold',
        'default_geofence_radius',
        'route_data_retention_days',
        'location_data_retention_days',
        'route_recording_enabled',
        'live_tracking_enabled',
        'map_provider',
        'google_maps_key',
    ];

    protected $casts = [
        'location_ping_interval'       => 'integer',
        'poor_accuracy_threshold'      => 'integer',
        'default_geofence_radius'      => 'integer',
        'route_data_retention_days'    => 'integer',
        'location_data_retention_days' => 'integer',
        'route_recording_enabled'      => 'boolean',
        'live_tracking_enabled'        => 'boolean',
    ];

    /**
     * Return the GPS settings for the current company, or sensible defaults.
     */
    public static function forCompany(?int $companyId): self
    {
        if ($companyId) {
            return static::firstOrNew(['company_id' => $companyId]);
        }
        return new static();
    }
}
