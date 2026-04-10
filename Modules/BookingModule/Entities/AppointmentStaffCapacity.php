<?php

namespace Modules\BookingModule\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\BookingModule\Traits\CompanyScoped;

class AppointmentStaffCapacity extends Model
{
    use CompanyScoped;
    use HasFactory;

    protected $table = 'appointment_staff_capacities';

    protected $fillable = [
        'company_id',
        'user_id',
        'max_per_day',
        'max_per_slot',
        'enforce_conflicts',
        'count_pending_too',
        'workspace',
        'created_by',
    ];

    protected $casts = [
        'enforce_conflicts' => 'boolean',
        'count_pending_too' => 'boolean',
        'max_per_day' => 'integer',
        'max_per_slot' => 'integer',
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