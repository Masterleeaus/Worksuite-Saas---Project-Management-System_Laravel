<?php

namespace Modules\TitanAgents\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatbotKnowledgeBaseArticle extends Model
{
    use SoftDeletes;

    protected $table = 'chatbot_knowledge_base_articles';

    protected $fillable = [
        'chatbot_id',
        'title',
        'content',
        'category',
        'tags',
        'status',
        'embedding_status',
        'views',
        'helpful_count',
        'created_by_id',
        'updated_by_id',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function chatbot(): BelongsTo
    {
        return $this->belongsTo(Chatbot::class);
    }

    public function embedding(): HasOne
    {
        return $this->hasOne(ChatbotEmbedding::class, 'source_id')
            ->where('source_type', 'article');
    }
}
