<?php

namespace Modules\TitanTalk\Models;

use App\Models\BaseModel;
use App\Models\User;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TitanTalkMessage extends BaseModel
{
    use HasCompany;
    use SoftDeletes;

    protected $table = 'titan_talk_messages';

    protected $guarded = ['id'];

    protected $casts = [
        'is_edited'         => 'boolean',
        'thread_reply_count' => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function room(): BelongsTo
    {
        return $this->belongsTo(TitanTalkRoom::class, 'room_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(TitanTalkMessageFile::class, 'message_id');
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(TitanTalkMessageReaction::class, 'message_id');
    }

    public function saves(): HasMany
    {
        return $this->hasMany(TitanTalkMessageSave::class, 'message_id');
    }

    public function threadReplies(): HasMany
    {
        return $this->hasMany(static::class, 'thread_parent_id');
    }

    public function threadParent(): BelongsTo
    {
        return $this->belongsTo(static::class, 'thread_parent_id');
    }

    public function pin(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(TitanTalkRoomPin::class, 'message_id');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    public function isPinned(): bool
    {
        return $this->pin()->exists();
    }

    public function isSavedBy(int $userId): bool
    {
        return TitanTalkMessageSave::where('user_id', $userId)
            ->where('message_id', $this->id)
            ->exists();
    }

    /**
     * Grouped emoji reactions summary: [emoji => count]
     */
    public function reactionSummary(): array
    {
        return $this->reactions()
            ->selectRaw('emoji, count(*) as count')
            ->groupBy('emoji')
            ->pluck('count', 'emoji')
            ->toArray();
    }
}
