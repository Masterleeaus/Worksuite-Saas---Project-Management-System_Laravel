<?php

namespace Modules\TitanAgents\Http\Controllers\Chatbot;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\TitanAgents\Models\Chatbot;
use Modules\TitanAgents\Models\ChatbotChannel;

class ChatbotMultiChannelController extends AccountBaseController
{
    public function index(Chatbot $chatbot)
    {
        $channels = $chatbot->channels()->get();

        return view('titanagents::chatbot.channels.index', compact('chatbot', 'channels'));
    }

    public function store(Request $request, Chatbot $chatbot)
    {
        $data = $request->validate([
            'channel_type'       => 'required|in:web,telegram,whatsapp,messenger',
            'channel_identifier' => 'nullable|string|max:255',
            'webhook_url'        => 'nullable|url|max:500',
        ]);

        $data['chatbot_id'] = $chatbot->id;

        ChatbotChannel::updateOrCreate(
            ['chatbot_id' => $chatbot->id, 'channel_type' => $data['channel_type']],
            $data
        );

        return back()->with('success', __('Channel configured.'));
    }

    public function destroy(Chatbot $chatbot, ChatbotChannel $channel)
    {
        $channel->delete();

        return back()->with('success', __('Channel removed.'));
    }
}
