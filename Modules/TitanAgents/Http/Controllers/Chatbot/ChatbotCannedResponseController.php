<?php

namespace Modules\TitanAgents\Http\Controllers\Chatbot;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\TitanAgents\Models\Chatbot;
use Modules\TitanAgents\Models\ChatbotCannedResponse;

class ChatbotCannedResponseController extends AccountBaseController
{
    public function index(Chatbot $chatbot)
    {
        $responses = $chatbot->cannedResponses()->latest()->paginate(20);

        return view('titanagents::chatbot.canned.index', compact('chatbot', 'responses'));
    }

    public function store(Request $request, Chatbot $chatbot)
    {
        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'content'  => 'required|string',
            'shortcut' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:100',
        ]);

        $data['chatbot_id']    = $chatbot->id;
        $data['created_by_id'] = auth()->id();

        ChatbotCannedResponse::create($data);

        return back()->with('success', __('Canned response created.'));
    }

    public function update(Request $request, Chatbot $chatbot, ChatbotCannedResponse $cannedResponse)
    {
        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'content'  => 'required|string',
            'shortcut' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:100',
            'status'   => 'in:active,inactive',
        ]);

        $cannedResponse->update($data);

        return back()->with('success', __('Updated.'));
    }

    public function destroy(Chatbot $chatbot, ChatbotCannedResponse $cannedResponse)
    {
        $cannedResponse->delete();

        return back()->with('success', __('Deleted.'));
    }
}
