<?php

namespace Modules\TitanPay\Models;

use App\Models\BaseModel;
use App\Models\Invoice;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentLink extends BaseModel
{
    use HasCompany;

    protected $table = 'payment_links';

    protected $guarded = ['id'];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at'    => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isUsed(): bool
    {
        return $this->used_at !== null;
    }

    public function isValid(): bool
    {
        return $this->status === 'active' && !$this->isExpired() && !$this->isUsed();
    }

    public function markUsed(): void
    {
        $this->update(['used_at' => now(), 'status' => 'used']);
    }
}
