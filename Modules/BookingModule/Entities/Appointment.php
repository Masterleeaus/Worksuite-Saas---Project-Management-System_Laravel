<?php

namespace Modules\BookingModule\Entities;

use App\Models\User;
use Modules\BookingModule\Entities\AppointmentAssignment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\BookingModule\Traits\CompanyScoped;

class Appointment extends Model
{
    use CompanyScoped;
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'question',
        'appointment_type',
        'date',
        'week_day',
        'start_time',
        'end_time',
        'is_enabled',
        'workspace',
        'created_by',
        'assigned_to',
        'assigned_by',
        'assigned_at',
        'assignment_status'
    ];

    protected static function newFactory()
    {
        return \Modules\BookingModule\Database\factories\AppointmentFactory::new();
    }

    public static $appointment_type = [
        'free' => 'Free',
        'paid' => 'Paid'
    ];

    public static $week_day = [
        'monday' => 'Monday',
        'tuesday' => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday' => 'Thursday',
        'friday' => 'Friday',
        'saturday' => 'Saturday',
        'sunday' => 'Sunday',
    ];

    public function creatorName()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }


    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function assignments()
    {
        return $this->hasMany(AppointmentAssignment::class, 'appointment_id')->latest();
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeUnassigned($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('assigned_to')->orWhere('assignment_status', 'unassigned');
        });
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