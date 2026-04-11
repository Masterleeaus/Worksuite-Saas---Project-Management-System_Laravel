<?php

namespace Modules\StaffCompliance\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class WorkerComplianceDocument extends Model
{
    protected $fillable = [
        'user_id',
        'document_type_id',
        'document_number',
        'issuing_authority',
        'issue_date',
        'expiry_date',
        'file_path',
        'status',
        'verified_by',
        'verified_at',
        'rejection_reason',
        'notes',
    ];

    protected $casts = [
        'issue_date'  => 'date',
        'expiry_date' => 'date',
        'verified_at' => 'datetime',
    ];

    public function worker()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function documentType()
    {
        return $this->belongsTo(ComplianceDocumentType::class, 'document_type_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function scopePendingReview($query)
    {
        return $query->where('status', 'pending_review');
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'verified')
                     ->whereNotNull('expiry_date')
                     ->where('expiry_date', '<', now()->toDateString());
    }

    public function scopeExpiringSoon($query, int $days = 30)
    {
        return $query->where('status', 'verified')
                     ->whereNotNull('expiry_date')
                     ->whereBetween('expiry_date', [now()->toDateString(), now()->addDays($days)->toDateString()]);
    }
}
