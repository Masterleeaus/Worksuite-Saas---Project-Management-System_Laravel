<?php

namespace Modules\Payroll\Entities;

use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayrollRunLineItem extends BaseModel
{
    protected $guarded = ['id'];

    protected $dates = ['job_date', 'job_start', 'job_end'];

    protected $casts = [
        'is_public_holiday' => 'boolean',
        'is_overridden' => 'boolean',
    ];

    public function payrollRun(): BelongsTo
    {
        return $this->belongsTo(PayrollRun::class, 'payroll_run_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function overrides(): HasMany
    {
        return $this->hasMany(PayrollRunOverride::class, 'line_item_id');
    }

    public function latestOverride(): BelongsTo
    {
        return $this->belongsTo(PayrollRunOverride::class, 'id', 'line_item_id');
    }
}
