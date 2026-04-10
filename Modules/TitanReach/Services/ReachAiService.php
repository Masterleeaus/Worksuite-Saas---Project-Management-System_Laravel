<?php

namespace Modules\TitanReach\Services;

use Modules\TitanReach\Models\ReachConversation;

class ReachAiService
{
    /**
     * Generate campaign content using AI (TitanZero if available).
     */
    public function generateCampaignContent(string $brief, string $channel, ?string $audience = null): string
    {
        if ($this->isTitanZeroAvailable()) {
            return $this->callTitanZero('generate_campaign', [
                'brief'    => $brief,
                'channel'  => $channel,
                'audience' => $audience,
            ]);
        }

        return '[AI not configured] Please set up TitanZero or TITANREACH_AI_GATEWAY_ENDPOINT to generate content.';
    }

    /**
     * Suggest a reply for the given conversation.
     */
    public function suggestReply(int $conversationId): string
    {
        $conversation = ReachConversation::with('messages')->find($conversationId);

        if (!$conversation) {
            return '';
        }

        $lastMessages = $conversation->messages->takeLast(5)->map(fn ($m) => $m->direction . ': ' . $m->content)->implode("\n");

        if ($this->isTitanZeroAvailable()) {
            return $this->callTitanZero('suggest_reply', ['conversation' => $lastMessages]);
        }

        return '[AI not configured] Last messages: ' . $lastMessages;
    }

    private function isTitanZeroAvailable(): bool
    {
        // Check for TitanZero module or a configured gateway endpoint.
        if (!empty(config('titanreach.ai.gateway_endpoint'))) {
            return (bool) config('titanreach.ai.enabled', false);
        }

        return class_exists('Modules\\TitanZero\\Services\\ZeroGateway');
    }

    /**
     * @param  array<string,mixed>  $payload
     */
    private function callTitanZero(string $action, array $payload): string
    {
        // Try to use TitanZero ZeroGateway if available.
        if (class_exists('Modules\\TitanZero\\Services\\ZeroGateway')) {
            /** @var object $gateway */
            $gateway = app('Modules\\TitanZero\\Services\\ZeroGateway');
            if (method_exists($gateway, 'ask')) {
                return (string) $gateway->ask(json_encode($payload));
            }
        }

        // Fall back to configured HTTP gateway endpoint.
        $endpoint = (string) config('titanreach.ai.gateway_endpoint', '');
        if (empty($endpoint)) {
            return '';
        }

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array_merge(['action' => $action], $payload)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $resp = curl_exec($ch);
        curl_close($ch);

        if ($resp === false) {
            return '';
        }

        $data = json_decode((string) $resp, true);
        return is_array($data) ? ($data['result'] ?? $data['content'] ?? '') : (string) $resp;
    }
}
