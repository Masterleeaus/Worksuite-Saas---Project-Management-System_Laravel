<?php

namespace Modules\TitanAgents\Http\Controllers\Chatbot;

use App\Http\Controllers\AccountBaseController;
use Modules\TitanAgents\Models\Chatbot;
use Modules\TitanAgents\Models\ChatbotCustomer;

class ChatbotCustomerController extends AccountBaseController
{
    public function index(Chatbot $chatbot)
    {
        $customers = ChatbotCustomer::where('chatbot_id', $chatbot->id)
            ->withCount('conversations')
            ->latest()
            ->paginate(20);

        return view('titanagents::chatbot.customers.index', compact('chatbot', 'customers'));
    }

    public function show(Chatbot $chatbot, ChatbotCustomer $customer)
    {
        $conversations = $customer->conversations()->with('history')->latest()->paginate(10);

        return view('titanagents::chatbot.customers.show', compact('chatbot', 'customer', 'conversations'));
    }

    public function destroy(Chatbot $chatbot, ChatbotCustomer $customer)
    {
        $customer->delete();

        return redirect()->route('titanagents.chatbot.customers.index', $chatbot)
            ->with('success', __('Customer deleted.'));
    }
}
