<?php

namespace Modules\ServicemanModule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Task;
use App\Models\User;

class JobChecklist extends Model
{
    protected $table = 'job_checklists';

    protected $fillable = [
        'task_id',
        'title',
        'created_by',
    ];

    /**
     * The task this checklist belongs to.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    /**
     * The user who created this checklist.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Checklist items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(JobChecklistItem::class, 'job_checklist_id')->orderBy('sort_order');
    }

    /**
     * Completed items.
     */
    public function completedItems(): HasMany
    {
        return $this->hasMany(JobChecklistItem::class, 'job_checklist_id')->where('is_completed', true);
    }

    /**
     * Completion percentage 0-100.
     */
    public function getCompletionPercentageAttribute(): int
    {
        $total = $this->items()->count();

        if ($total === 0) {
            return 0;
        }

        return (int) round(($this->completedItems()->count() / $total) * 100);
    }
}
