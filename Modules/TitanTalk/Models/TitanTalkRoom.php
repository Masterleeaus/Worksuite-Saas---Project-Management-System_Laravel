<?php

namespace Modules\TitanTalk\Models;

use App\Models\BaseModel;
use App\Models\User;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TitanTalkRoom extends BaseModel
{
    use HasCompany;
    use SoftDeletes;

    protected $table = 'titan_talk_rooms';

    protected $guarded = ['id'];

    protected $casts = [
        'is_archived' => 'boolean',
    ];

    public const ROOM_TYPES = [
        'public', 'private', 'dm', 'team', 'department',
        'project', 'booking', 'service_job', 'site',
        'worker_team', 'issue', 'private_group',
        'company', 'office', 'dispatch', 'finance', 'sales',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'titan_talk_room_members', 'room_id', 'user_id')
            ->withPivot(['role', 'is_muted', 'last_read_at'])
            ->withTimestamps();
    }

    public function roomMembers(): HasMany
    {
        return $this->hasMany(TitanTalkRoomMember::class, 'room_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TitanTalkMessage::class, 'room_id')->whereNull('thread_parent_id');
    }

    public function allMessages(): HasMany
    {
        return $this->hasMany(TitanTalkMessage::class, 'room_id');
    }

    public function latestMessage(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(TitanTalkMessage::class, 'room_id')->latest();
    }

    public function pins(): HasMany
    {
        return $this->hasMany(TitanTalkRoomPin::class, 'room_id');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    public function isMember(int $userId): bool
    {
        return $this->roomMembers()->where('user_id', $userId)->exists();
    }

    public function getMemberRole(int $userId): ?string
    {
        return $this->roomMembers()->where('user_id', $userId)->value('role');
    }

    public function unreadCountForUser(int $userId): int
    {
        $member = $this->roomMembers()->where('user_id', $userId)->first();
        if (!$member) {
            return 0;
        }
        $query = $this->messages()->where('user_id', '!=', $userId);
        if ($member->last_read_at) {
            $query->where('created_at', '>', $member->last_read_at);
        }
        return $query->count();
    }

    public static function accessibleByUser(int $userId, int $companyId): \Illuminate\Database\Eloquent\Builder
    {
        return static::where('company_id', $companyId)
            ->where(function ($q) use ($userId) {
                $q->where('type', 'public')
                  ->orWhereHas('roomMembers', fn($q2) => $q2->where('user_id', $userId));
            });
    }
}
