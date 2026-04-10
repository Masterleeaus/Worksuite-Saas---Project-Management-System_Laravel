<?php

namespace Modules\CyberSecurity\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class DataPrivacyRequest extends BaseModel
{
    use HasFactory, HasCompany;

    protected $guarded = ['id'];

    protected $casts = [
        'due_date'     => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function handledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'access'        => 'Right of Access',
            'deletion'      => 'Right to Erasure (Deletion)',
            'rectification' => 'Right to Rectification',
            'portability'   => 'Data Portability',
            default         => ucfirst($this->type),
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'completed'   => 'success',
            'in_progress' => 'info',
            'rejected'    => 'danger',
            default       => 'warning',
        };
    }
}
