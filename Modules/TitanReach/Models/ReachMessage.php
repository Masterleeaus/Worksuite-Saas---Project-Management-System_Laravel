<?php

namespace Modules\TitanReach\Models;

use Illuminate\Database\Eloquent\Model;

class ReachMessage extends Model
{
    protected $table = 'reach_messages';

    protected $fillable = [
        'conversation_id', 'direction', 'content', 'media_url',
        'channel', 'sent_at', 'delivered_at', 'read_at', 'meta',
    ];

    protected $casts = [
        'sent_at'      => 'datetime',
        'delivered_at' => 'datetime',
        'read_at'      => 'datetime',
        'meta'         => 'array',
    ];

    public function conversation()
    {
        return $this->belongsTo(ReachConversation::class, 'conversation_id');
    }
}
