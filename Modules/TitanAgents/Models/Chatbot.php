<?php

namespace Modules\TitanAgents\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chatbot extends Model
{
    use HasCompany, SoftDeletes;

    protected $table = 'chatbots';

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'ai_provider',
        'ai_model',
        'system_prompt',
        'welcome_message',
        'fallback_message',
        'temperature',
        'max_tokens',
        'status',
        'plan_limit',
        'settings',
        'created_by_id',
        'updated_by_id',
    ];

    protected $casts = [
        'temperature' => 'decimal:2',
        'settings'    => 'array',
    ];

    public function channels(): HasMany
    {
        return $this->hasMany(ChatbotChannel::class);
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(ChatbotConversation::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(ChatbotCustomer::class);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(ChatbotKnowledgeBaseArticle::class);
    }

    public function cannedResponses(): HasMany
    {
        return $this->hasMany(ChatbotCannedResponse::class);
    }

    public function avatar(): HasOne
    {
        return $this->hasOne(ChatbotAvatar::class);
    }

    public function webhooks(): HasMany
    {
        return $this->hasMany(ChatbotChannelWebhook::class);
    }

    public function pageVisits(): HasMany
    {
        return $this->hasMany(ChatbotPageVisit::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForCompany($query, int $companyId)
    {
        return $query->where('company_id', $companyId);
    }
}
