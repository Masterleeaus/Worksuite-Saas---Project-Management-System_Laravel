<?php

namespace Modules\CustomerConnect\Services\AI;

use Modules\CustomerConnect\Entities\MessageIntent;

class IntentDetector
{
    public function storeIntent(array $tenant, int $threadId, ?int $messageId, string $intentKey, int $confidence = 0, ?array $payload = null): MessageIntent
    {
        return MessageIntent::create([
            'company_id' => $tenant['company_id'],
            'user_id'    => $tenant['user_id'],
            'thread_id'  => $threadId,
            'message_id' => $messageId,
            'intent_key' => $intentKey,
            'confidence' => max(0, min(100, $confidence)),
            'payload'    => $payload,
        ]);
    }
}
