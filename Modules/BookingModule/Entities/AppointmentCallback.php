<?php

namespace Modules\BookingModule\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\BookingModule\Traits\CompanyScoped;

class AppointmentCallback extends Model
{
    use CompanyScoped;
    use HasFactory;

    protected $fillable = [
        'company_id',
        'schedule_id',
        'unique_id',
        'user_id',
        'appointment_id',
        'reason',
        'date',
        'start_time',
        'end_time',
        'start_url',
        'join_url',
        'workspace',
        'created_by'
    ];

    protected static function newFactory()
    {
        return \Modules\BookingModule\Database\factories\AppointmentCallbackFactory::new();
    }

    public function appointment()
    {
        return $this->hasOne('Modules\BookingModule\Entities\Appointment', 'id', 'appointment_id');
    }

    public function schedule()
    {
        return $this->hasOne('Modules\BookingModule\Entities\Schedule', 'id', 'schedule_id');
    }

    public function creatorName()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function users()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

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