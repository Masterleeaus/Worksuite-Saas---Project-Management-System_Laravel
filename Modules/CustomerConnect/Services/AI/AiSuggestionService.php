<?php

namespace Modules\CustomerConnect\Services\AI;

use Illuminate\Support\Facades\Config;
use Modules\CustomerConnect\Entities\AiSuggestion;

class AiSuggestionService
{
    public function enabled(): bool
    {
        return (bool) Config::get('customerconnect.ai.enabled', false);
    }

    public function createReplySuggestion(array $tenant, int $threadId, ?int $messageId, string $title, string $replyText, ?array $payload = null): AiSuggestion
    {
        return AiSuggestion::create([
            'company_id' => $tenant['company_id'],
            'user_id'    => $tenant['user_id'],
            'thread_id'  => $threadId,
            'message_id' => $messageId,
            'type'       => 'reply',
            'title'      => $title,
            'suggestion' => $replyText,
            'payload'    => $payload,
            'status'     => 'draft',
        ]);
    }

    public function createActionSuggestion(array $tenant, int $threadId, ?int $messageId, string $title, array $actionPayload, ?array $risk = null): AiSuggestion
    {
        $payload = $actionPayload;
        if ($risk) {
            $payload['_risk'] = $risk;
        }

        return AiSuggestion::create([
            'company_id' => $tenant['company_id'],
            'user_id'    => $tenant['user_id'],
            'thread_id'  => $threadId,
            'message_id' => $messageId,
            'type'       => 'action',
            'title'      => $title,
            'payload'    => $payload,
            'status'     => 'draft',
        ]);
    }
}
