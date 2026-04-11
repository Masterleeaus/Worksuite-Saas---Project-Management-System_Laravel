<?php

namespace Modules\TitanAgents\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ChatbotAvatar extends Model
{
    protected $table = 'chatbot_avatars';

    protected $fillable = [
        'chatbot_id',
        'filename',
        'original_name',
        'mime_type',
        'file_size',
    ];

    public function chatbot(): BelongsTo
    {
        return $this->belongsTo(Chatbot::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::url('chatbot-avatars/' . $this->filename);
    }
}
