<?php

namespace Modules\TitanAgents\Http\Controllers\Chatbot;

use App\Http\Controllers\AccountBaseController;
use Modules\TitanAgents\Models\Chatbot;
use Modules\TitanAgents\Models\ChatbotConversation;

class ConversationInboxController extends AccountBaseController
{
    public function index(Chatbot $chatbot)
    {
        $conversations = ChatbotConversation::where('chatbot_id', $chatbot->id)
            ->with('customer')
            ->latest()
            ->paginate(25);

        return view('titanagents::chatbot.inbox.index', compact('chatbot', 'conversations'));
    }

    public function show(Chatbot $chatbot, ChatbotConversation $conversation)
    {
        $messages  = $conversation->history()->orderBy('created_at')->get();
        $customer  = $conversation->customer;

        return view('titanagents::chatbot.inbox.show', compact('chatbot', 'conversation', 'messages', 'customer'));
    }

    public function resolve(Chatbot $chatbot, ChatbotConversation $conversation)
    {
        $conversation->update(['status' => 'resolved', 'ended_at' => now()]);
        return back()->with('success', 'Conversation resolved.');
    }

    public function escalate(Chatbot $chatbot, ChatbotConversation $conversation)
    {
        $conversation->update(['status' => 'escalated']);
        return back()->with('success', 'Conversation escalated.');
    }
}
