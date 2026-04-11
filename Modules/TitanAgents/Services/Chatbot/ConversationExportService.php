<?php

namespace Modules\TitanAgents\Services\Chatbot;

use Modules\TitanAgents\Models\Chatbot;
use Modules\TitanAgents\Models\ChatbotConversation;

class ConversationExportService
{
    public function exportToCsv(Chatbot $chatbot, array $filters = []): string
    {
        $conversations = $this->getFilteredConversations($chatbot, $filters);

        $lines = [implode(',', ['ID', 'Session', 'Channel', 'Status', 'Customer', 'Messages', 'Started At', 'Ended At'])];

        foreach ($conversations as $conv) {
            $lines[] = implode(',', [
                $conv->id,
                $conv->session_id,
                $conv->channel_type,
                $conv->status,
                $conv->customer?->name ?? 'Anonymous',
                $conv->message_count,
                $conv->started_at?->toDateTimeString() ?? '',
                $conv->ended_at?->toDateTimeString() ?? '',
            ]);
        }

        return implode("\n", $lines);
    }

    public function exportToJson(Chatbot $chatbot, array $filters = []): string
    {
        $conversations = $this->getFilteredConversations($chatbot, $filters)->load('history', 'customer');

        return $conversations->toJson(JSON_PRETTY_PRINT);
    }

    protected function getFilteredConversations(Chatbot $chatbot, array $filters)
    {
        $query = ChatbotConversation::where('chatbot_id', $chatbot->id)->with('customer');

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['from'])) {
            $query->where('created_at', '>=', $filters['from']);
        }

        if (! empty($filters['to'])) {
            $query->where('created_at', '<=', $filters['to']);
        }

        return $query->latest()->get();
    }
}
