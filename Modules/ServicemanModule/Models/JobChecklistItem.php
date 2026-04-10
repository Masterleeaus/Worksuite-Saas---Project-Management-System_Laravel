<?php

namespace Modules\ServicemanModule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class JobChecklistItem extends Model
{
    protected $table = 'job_checklist_items';

    protected $fillable = [
        'job_checklist_id',
        'label',
        'is_completed',
        'completed_by',
        'completed_at',
        'sort_order',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    /**
     * The parent checklist.
     */
    public function checklist(): BelongsTo
    {
        return $this->belongsTo(JobChecklist::class, 'job_checklist_id');
    }

    /**
     * The user who completed this item.
     */
    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }
}
