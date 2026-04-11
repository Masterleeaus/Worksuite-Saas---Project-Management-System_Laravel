<?php

namespace Modules\TitanAgents\Services\Chatbot;

use Illuminate\Support\Str;
use Modules\TitanAgents\Models\Chatbot;
use Modules\TitanAgents\Models\ChatbotConversation;
use Modules\TitanAgents\Models\ChatbotCustomer;
use Modules\TitanAgents\Models\ChatbotHistory;

class ChatbotService
{
    public function __construct(protected GeneratorService $generatorService) {}

    public function startConversation(Chatbot $chatbot, array $customerData = [], string $channelType = 'web'): ChatbotConversation
    {
        $customer = null;

        if (! empty($customerData)) {
            $customer = ChatbotCustomer::firstOrCreate(
                [
                    'chatbot_id'          => $chatbot->id,
                    'channel_type'        => $channelType,
                    'channel_customer_id' => $customerData['channel_customer_id'] ?? null,
                ],
                array_merge(['chatbot_id' => $chatbot->id, 'channel_type' => $channelType], $customerData)
            );
        }

        return ChatbotConversation::create([
            'chatbot_id'   => $chatbot->id,
            'customer_id'  => $customer?->id,
            'channel_type' => $channelType,
            'session_id'   => Str::uuid()->toString(),
            'status'       => 'open',
            'started_at'   => now(),
        ]);
    }

    public function chat(ChatbotConversation $conversation, string $userMessage): array
    {
        $chatbot = $conversation->chatbot;

        // Store user message
        ChatbotHistory::create([
            'conversation_id' => $conversation->id,
            'role'            => 'user',
            'content'         => $userMessage,
        ]);

        // Build messages array
        $messages = [];

        if ($chatbot->system_prompt) {
            $messages[] = ['role' => 'system', 'content' => $chatbot->system_prompt];
        }

        // Add conversation history (last 10)
        $history = ChatbotHistory::where('conversation_id', $conversation->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->reverse()
            ->values();

        foreach ($history as $h) {
            $messages[] = ['role' => $h->role, 'content' => $h->content];
        }

        // Generate response
        $result = $this->generatorService->generate($chatbot->ai_provider, $messages, [
            'model'       => $chatbot->ai_model,
            'temperature' => $chatbot->temperature,
            'max_tokens'  => $chatbot->max_tokens,
        ]);

        // Store assistant message
        $tokenCount = $result['usage']['total_tokens'] ?? null;

        ChatbotHistory::create([
            'conversation_id' => $conversation->id,
            'role'            => 'assistant',
            'content'         => $result['content'],
            'token_count'     => $tokenCount,
        ]);

        // Update conversation message count
        $conversation->increment('message_count', 2);

        return $result;
    }

    public function resolveConversation(ChatbotConversation $conversation, string $notes = ''): void
    {
        $conversation->update([
            'status'           => 'resolved',
            'ended_at'         => now(),
            'resolution_notes' => $notes,
        ]);
    }

    public function escalateConversation(ChatbotConversation $conversation): void
    {
        $conversation->update(['status' => 'escalated']);
    }
}
