<?php

namespace Modules\TitanPWA\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * SyncQueueItem
 *
 * Server-side shadow of the client-side IndexedDB sync queue.
 * Items are created when offline mutations are replayed on reconnect and
 * are deleted once they have been successfully processed.
 *
 * @property int    $id
 * @property int    $user_id
 * @property string $type
 * @property array  $payload
 * @property string $status   pending|processing|processed|failed
 * @property int    $attempts
 * @property string|null $last_error
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class SyncQueueItem extends Model
{
    protected $table = 'pwa_sync_queue';

    protected $fillable = [
        'user_id',
        'type',
        'payload',
        'status',
        'attempts',
        'last_error',
    ];

    protected $casts = [
        'payload'  => 'array',
        'attempts' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Scope: only pending items.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: only failed items.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
