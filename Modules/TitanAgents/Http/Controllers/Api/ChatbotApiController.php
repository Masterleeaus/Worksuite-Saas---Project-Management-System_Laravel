<?php

namespace Modules\TitanAgents\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\TitanAgents\Models\Chatbot;
use Modules\TitanAgents\Models\ChatbotConversation;
use Modules\TitanAgents\Models\ChatbotPageVisit;
use Modules\TitanAgents\Services\Chatbot\ChatbotService;

class ChatbotApiController extends Controller
{
    public function __construct(protected ChatbotService $chatbotService) {}

    public function widget(Request $request, string $chatbotId)
    {
        $chatbot = Chatbot::where('id', $chatbotId)->where('status', 'active')->firstOrFail();

        ChatbotPageVisit::create([
            'chatbot_id' => $chatbot->id,
            'session_id' => $request->input('session_id'),
            'page_url'   => $request->input('page_url'),
            'referrer'   => $request->input('referrer'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'visited_at' => now(),
        ]);

        return response()->json([
            'chatbot_id'      => $chatbot->id,
            'name'            => $chatbot->name,
            'welcome_message' => $chatbot->welcome_message ?? 'Hello! How can I help you today?',
        ]);
    }

    public function startConversation(Request $request, string $chatbotId)
    {
        $chatbot      = Chatbot::where('id', $chatbotId)->where('status', 'active')->firstOrFail();
        $customerData = $request->only(['name', 'email', 'phone', 'channel_customer_id']);
        $conversation = $this->chatbotService->startConversation($chatbot, $customerData, $request->input('channel', 'web'));

        return response()->json([
            'session_id'      => $conversation->session_id,
            'conversation_id' => $conversation->id,
        ]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
            'message'    => 'required|string|max:2000',
        ]);

        $conversation = ChatbotConversation::where('session_id', $request->session_id)
            ->where('status', 'open')
            ->firstOrFail();

        try {
            $result = $this->chatbotService->chat($conversation, $request->message);

            return response()->json([
                'reply'    => $result['content'],
                'provider' => $result['provider'] ?? 'unknown',
            ]);
        } catch (\Throwable $e) {
            $chatbot = $conversation->chatbot;

            return response()->json([
                'reply' => $chatbot->fallback_message ?? 'Sorry, I am unable to respond right now. Please try again later.',
            ], 200);
        }
    }

    public function getCannedResponses(string $chatbotId)
    {
        $chatbot   = Chatbot::findOrFail($chatbotId);
        $responses = $chatbot->cannedResponses()
            ->where('status', 'active')
            ->get(['id', 'title', 'shortcut', 'content']);

        return response()->json($responses);
    }
}
