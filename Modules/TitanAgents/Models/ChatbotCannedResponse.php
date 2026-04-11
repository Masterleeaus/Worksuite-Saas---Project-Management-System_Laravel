<?php

namespace Modules\TitanAgents\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatbotCannedResponse extends Model
{
    use SoftDeletes;

    protected $table = 'chatbot_canned_responses';

    protected $fillable = [
        'chatbot_id',
        'shortcut',
        'title',
        'content',
        'category',
        'use_count',
        'status',
        'created_by_id',
    ];

    public function chatbot(): BelongsTo
    {
        return $this->belongsTo(Chatbot::class);
    }
}
