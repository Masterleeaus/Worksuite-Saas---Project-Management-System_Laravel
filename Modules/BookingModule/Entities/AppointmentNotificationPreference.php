<?php

namespace Modules\BookingModule\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\BookingModule\Traits\CompanyScoped;

class AppointmentNotificationPreference extends Model
{
    use CompanyScoped;
    protected $table = 'appointment_notification_preferences';

    protected $fillable = [
        'company_id',
        'user_id',
        'channel_email',
        'channel_database',
        'notify_assigned',
        'notify_reassigned',
        'notify_unassigned',
        'notify_rescheduled',
        'notify_cancelled',
        'daily_digest',
        'quiet_hours_start',
        'quiet_hours_end',
    ];

    protected $casts = [
        'channel_email' => 'boolean',
        'channel_database' => 'boolean',
        'notify_assigned' => 'boolean',
        'notify_reassigned' => 'boolean',
        'notify_unassigned' => 'boolean',
        'notify_rescheduled' => 'boolean',
        'notify_cancelled' => 'boolean',
        'daily_digest' => 'boolean',
    ];

    protected static function booted(): void
    {
        // Tenant safety: scope queries to current company when available.
        static::addGlobalScope('company', function ($query) {
            $companyId = null;

            try {
                if (function_exists('company') && company()) {
                    $companyId = company()->id;
                } elseif (auth()->check()) {
                    $companyId = auth()->user()->company_id ?? (auth()->user()->company->id ?? null);
                }
            } catch (\Throwable $e) {
                $companyId = null;
            }

            if ($companyId) {
                $query->where($query->getModel()->getTable() . '.company_id', $companyId);
            }
        });

        // Auto-fill company_id for new rows when possible.
        static::creating(function ($model) {
            if (!isset($model->company_id) || !$model->company_id) {
                try {
                    if (function_exists('company') && company()) {
                        $model->company_id = company()->id;
                    } elseif (auth()->check()) {
                        $model->company_id = auth()->user()->company_id ?? (auth()->user()->company->id ?? null);
                    }
                } catch (\Throwable $e) {
                    // leave null
                }
            }
        });
    }

}