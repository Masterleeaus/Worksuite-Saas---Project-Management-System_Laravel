<?php

namespace Modules\TitanPay\Models;

use App\Models\BaseModel;
use App\Models\Invoice;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentQrCode extends BaseModel
{
    use HasCompany;

    protected $table = 'payment_qr_codes';

    protected $guarded = ['id'];

    protected $casts = [
        'expires_at'  => 'datetime',
        'paid_at'     => 'datetime',
        'scanned_at'  => 'datetime',
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

    public function isPaid(): bool
    {
        return $this->paid_at !== null;
    }

    public function markPaid(): void
    {
        $this->update(['paid_at' => now(), 'status' => 'used']);
    }

    public function recordScan(): void
    {
        $this->update([
            'scanned_at'  => $this->scanned_at ?? now(),
            'scan_count'  => $this->scan_count + 1,
        ]);
    }
}
