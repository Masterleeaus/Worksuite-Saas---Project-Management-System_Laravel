<?php

namespace Modules\ServicemanModule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Task;
use App\Models\User;

class JobPhoto extends Model
{
    protected $table = 'job_photos';

    protected $fillable = [
        'task_id',
        'uploaded_by',
        'type',
        'file_path',
        'caption',
    ];

    /**
     * The task this photo belongs to.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    /**
     * The user who uploaded the photo.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Return the public URL for this photo.
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}
