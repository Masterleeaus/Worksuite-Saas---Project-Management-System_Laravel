<?php

namespace Modules\Payroll\Entities;

use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollRunOverride extends BaseModel
{
    protected $guarded = ['id'];

    public function lineItem(): BelongsTo
    {
        return $this->belongsTo(PayrollRunLineItem::class, 'line_item_id');
    }

    public function overriddenBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'overridden_by');
    }
}
