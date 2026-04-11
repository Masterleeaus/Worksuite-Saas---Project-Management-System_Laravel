<?php

namespace Modules\TitanAgents\Http\Controllers\Chatbot;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\TitanAgents\Models\Chatbot;

class ChatbotController extends AccountBaseController
{
    public function index()
    {
        $chatbots = Chatbot::where('company_id', company()->id)->latest()->get();

        return view('titanagents::chatbot.index', compact('chatbots'));
    }

    public function create()
    {
        return view('titanagents::chatbot.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'ai_provider'      => 'required|in:openai,anthropic,gemini',
            'ai_model'         => 'nullable|string|max:100',
            'system_prompt'    => 'nullable|string',
            'welcome_message'  => 'nullable|string|max:500',
            'fallback_message' => 'nullable|string|max:500',
            'temperature'      => 'nullable|numeric|min:0|max:2',
            'max_tokens'       => 'nullable|integer|min:100|max:8000',
        ]);

        $data['company_id']    = company()->id;
        $data['created_by_id'] = auth()->id();

        $chatbot = Chatbot::create($data);

        return redirect()->route('titanagents.chatbot.show', $chatbot)
            ->with('success', __('Chatbot created successfully.'));
    }

    public function show(Chatbot $chatbot)
    {
        return view('titanagents::chatbot.show', compact('chatbot'));
    }

    public function edit(Chatbot $chatbot)
    {
        return view('titanagents::chatbot.edit', compact('chatbot'));
    }

    public function update(Request $request, Chatbot $chatbot)
    {

        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'ai_provider'      => 'required|in:openai,anthropic,gemini',
            'ai_model'         => 'nullable|string|max:100',
            'system_prompt'    => 'nullable|string',
            'welcome_message'  => 'nullable|string|max:500',
            'fallback_message' => 'nullable|string|max:500',
            'temperature'      => 'nullable|numeric|min:0|max:2',
            'max_tokens'       => 'nullable|integer|min:100|max:8000',
        ]);

        $data['updated_by_id'] = auth()->id();

        $chatbot->update($data);

        return redirect()->route('titanagents.chatbot.show', $chatbot)
            ->with('success', __('Chatbot updated successfully.'));
    }

    public function destroy(Chatbot $chatbot)
    {

        $chatbot->delete();

        return redirect()->route('titanagents.chatbot.index')
            ->with('success', __('Chatbot deleted.'));
    }
}
