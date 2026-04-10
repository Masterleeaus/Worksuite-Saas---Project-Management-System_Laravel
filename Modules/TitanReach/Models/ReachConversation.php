<?php

namespace Modules\TitanReach\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReachConversation extends Model
{
    use SoftDeletes;

    protected $table = 'reach_conversations';

    protected $fillable = [
        'company_id', 'contact_id', 'channel', 'external_id', 'status',
        'last_message', 'unread_count', 'assigned_to', 'meta',
    ];

    protected $casts = [
        'meta'         => 'array',
        'unread_count' => 'integer',
    ];

    public function messages()
    {
        return $this->hasMany(ReachMessage::class, 'conversation_id')->orderBy('id');
    }

    public function contact()
    {
        return $this->belongsTo(ReachContact::class, 'contact_id');
    }
}
