<?php

namespace Modules\TitanAgents\Services\Chatbot;

use Illuminate\Support\Facades\DB;
use Modules\TitanAgents\Models\Chatbot;
use Modules\TitanAgents\Models\ChatbotConversation;
use Modules\TitanAgents\Models\ChatbotPageVisit;

class ChatbotAnalyticsService
{
    public function getSummary(Chatbot $chatbot, string $period = '30d'): array
    {
        $since = match ($period) {
            '7d'    => now()->subDays(7),
            '30d'   => now()->subDays(30),
            '90d'   => now()->subDays(90),
            default => now()->subDays(30),
        };

        $conversations = ChatbotConversation::where('chatbot_id', $chatbot->id)->where('created_at', '>=', $since);
        $total         = (clone $conversations)->count();
        $resolved      = (clone $conversations)->where('status', 'resolved')->count();
        $escalated     = (clone $conversations)->where('status', 'escalated')->count();

        $avgMessages = (clone $conversations)->avg('message_count') ?? 0;

        $pageVisits = ChatbotPageVisit::where('chatbot_id', $chatbot->id)
            ->where('visited_at', '>=', $since)
            ->count();

        return [
            'period'                         => $period,
            'total_conversations'            => $total,
            'resolved_conversations'         => $resolved,
            'escalated_conversations'        => $escalated,
            'resolution_rate'                => $total > 0 ? round(($resolved / $total) * 100, 1) : 0,
            'escalation_rate'                => $total > 0 ? round(($escalated / $total) * 100, 1) : 0,
            'avg_messages_per_conversation'  => round($avgMessages, 1),
            'page_visits'                    => $pageVisits,
        ];
    }

    public function getConversationsByDay(Chatbot $chatbot, int $days = 30): array
    {
        return ChatbotConversation::where('chatbot_id', $chatbot->id)
            ->where('created_at', '>=', now()->subDays($days))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();
    }
}
