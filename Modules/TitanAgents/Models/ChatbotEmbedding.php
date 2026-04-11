<?php

namespace Modules\TitanAgents\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatbotEmbedding extends Model
{
    protected $table = 'chatbot_embeddings';

    protected $fillable = [
        'chatbot_id',
        'source_type',
        'source_id',
        'embedding_model',
        'vector_data',
        'checksum',
    ];

    public function chatbot(): BelongsTo
    {
        return $this->belongsTo(Chatbot::class);
    }

    public function getVectorArrayAttribute(): array
    {
        return $this->vector_data ? json_decode($this->vector_data, true) : [];
    }
}
