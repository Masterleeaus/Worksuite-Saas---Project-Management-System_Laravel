<?php

namespace Modules\TitanReach\Services;

use Modules\TitanReach\Models\ReachContact;
use Modules\TitanReach\Models\ReachConversation;
use Modules\TitanReach\Models\ReachMessage;

class TelegramService
{
    protected string $botToken;
    protected string $apiBase = 'https://api.telegram.org/bot';

    public function __construct()
    {
        $this->botToken = (string) config('titanreach.telegram.bot_token', '');
    }

    /**
     * Send a text message to a Telegram chat.
     *
     * @return array<string,mixed>
     */
    public function sendMessage(string $chatId, string $text): array
    {
        return $this->apiCall('sendMessage', [
            'chat_id' => $chatId,
            'text'    => $text,
        ]);
    }

    /**
     * Handle an inbound Telegram update and persist to the inbox.
     *
     * @param  array<string,mixed>  $update
     */
    public function receiveInbound(array $update): ReachMessage
    {
        $message  = $update['message'] ?? $update['edited_message'] ?? [];
        $chatId   = (string) ($message['chat']['id'] ?? '');
        $text     = $message['text'] ?? '';
        $from     = $message['from'] ?? [];
        $name     = trim(($from['first_name'] ?? '') . ' ' . ($from['last_name'] ?? '')) ?: $chatId;

        $contact = ReachContact::firstOrCreate(
            ['telegram_chat_id' => $chatId],
            ['name' => $name, 'company_id' => null]
        );

        $conversation = ReachConversation::firstOrCreate(
            ['contact_id' => $contact->id, 'channel' => 'telegram', 'status' => 'open'],
            ['company_id' => $contact->company_id, 'external_id' => $chatId]
        );

        $msg = $conversation->messages()->create([
            'direction' => 'inbound',
            'content'   => $text,
            'channel'   => 'telegram',
            'sent_at'   => now(),
            'meta'      => ['update_id' => $update['update_id'] ?? null, 'raw' => $update],
        ]);

        $conversation->update([
            'last_message' => substr($text, 0, 255),
            'unread_count' => $conversation->unread_count + 1,
        ]);

        return $msg;
    }

    /**
     * @param  array<string,mixed>  $params
     * @return array<string,mixed>
     */
    private function apiCall(string $method, array $params = []): array
    {
        $url = $this->apiBase . $this->botToken . '/' . $method;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $resp = curl_exec($ch);
        $err  = curl_error($ch);
        curl_close($ch);

        if ($resp === false) {
            throw new \RuntimeException('Telegram API error: ' . $err);
        }

        $data = json_decode((string) $resp, true);
        return is_array($data) ? $data : [];
    }
}
