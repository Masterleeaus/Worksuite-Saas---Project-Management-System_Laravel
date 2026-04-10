<?php

namespace Modules\BookingModule\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\BookingModule\Entities\ScheduleAssignment;
use Modules\BookingModule\Traits\CompanyScoped;

class Schedule extends Model
{
    use CompanyScoped;
    use HasFactory;

    protected $fillable = [
        'unique_id',
        'notes',
        'location',
        'timezone',
        'ends_at',
        'starts_at',
        'company_id',
        'user_id',
        'assigned_to',
        'assigned_by',
        'assigned_at',
        'assignment_status',
        'name',
        'email',
        'phone',
        'date',
        'start_time',
        'end_time',
        'appointment_id',
        'questions',
        'meeting_type',
        'start_url',
        'join_url',
        'cancel_description',
        'status',
        'send_feedback',
        'workspace',
        'created_by',
    ];

        protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'assigned_at' => 'datetime',
    ];

protected static function newFactory()
    {
        return \Modules\BookingModule\Database\factories\ScheduleFactory::new();
    }

    public function appointment()
    {
        return $this->hasOne('Modules\BookingModule\Entities\Appointment', 'id', 'appointment_id');
    }

    public function creatorName()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function users()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function assignee()
    {
        // Prefer new assigned_to, fallback to legacy user_id.
        return $this->hasOne(User::class, 'id', 'assigned_to');
    }

    public function assignments()
    {
        return $this->hasMany(ScheduleAssignment::class, 'schedule_id', 'id')->latest();
    }

    public function getEffectiveAssigneeIdAttribute(): ?int
    {
        return $this->assigned_to ? (int)$this->assigned_to : ($this->user_id ? (int)$this->user_id : null);
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