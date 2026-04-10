<?php

namespace Modules\Payroll\Entities;

use App\Models\BaseModel;
use App\Models\Company;
use App\Models\User;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayrollRun extends BaseModel
{
    use HasCompany;

    protected $guarded = ['id'];

    protected $dates = ['period_start', 'period_end', 'approved_at'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function lineItems(): HasMany
    {
        return $this->hasMany(PayrollRunLineItem::class, 'payroll_run_id');
    }

    public function isApproved(): bool
    {
        return in_array($this->status, ['approved', 'exported']);
    }
}
