<?php

namespace Modules\Payroll\Entities;

use App\Models\BaseModel;
use App\Models\Company;
use App\Models\User;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payslip extends BaseModel
{
    use HasCompany;

    protected $guarded = ['id'];

    protected $casts = [
        'line_items'       => 'array',
        'paid_at'          => 'datetime',
        'is_subcontractor' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function payrollRun(): BelongsTo
    {
        return $this->belongsTo(PayrollRun::class, 'payroll_run_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    /**
     * Whether this payslip has been paid.
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Whether this payslip belongs to a locked (approved/exported) payroll run.
     */
    public function isLocked(): bool
    {
        return optional($this->payrollRun)->isApproved() ?? false;
    }
}
