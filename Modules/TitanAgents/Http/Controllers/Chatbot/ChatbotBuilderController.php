<?php

namespace Modules\TitanAgents\Http\Controllers\Chatbot;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\TitanAgents\Models\Chatbot;

class ChatbotBuilderController extends AccountBaseController
{
    public function show(Chatbot $chatbot)
    {
        // Default appearance settings merged with saved settings
        $defaults = [
            'theme_color'       => '#6366f1',
            'header_text'       => $chatbot->name,
            'launcher_icon'     => 'bubble', // bubble | robot | heart
            'position'          => 'bottom-right', // bottom-right | bottom-left
            'window_width'      => 380,
            'window_height'     => 560,
            'font_size'         => 14,
            'user_bubble_color' => '#6366f1',
            'bot_bubble_color'  => '#f3f4f6',
            'user_text_color'   => '#ffffff',
            'bot_text_color'    => '#111827',
            'show_powered_by'   => true,
            'initial_open'      => false,
        ];
        $settings = array_merge($defaults, (array)($chatbot->settings ?? []));

        return view('titanagents::chatbot.builder.show', compact('chatbot', 'settings'));
    }

    public function update(Request $request, Chatbot $chatbot)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'ai_provider'       => 'required|in:openai,anthropic,gemini',
            'ai_model'          => 'nullable|string|max:100',
            'system_prompt'     => 'nullable|string',
            'welcome_message'   => 'nullable|string|max:500',
            'fallback_message'  => 'nullable|string|max:500',
            'temperature'       => 'nullable|numeric|min:0|max:2',
            'max_tokens'        => 'nullable|integer|min:100|max:8000',
            'status'            => 'in:active,inactive',
            // Appearance
            'settings.theme_color'       => 'nullable|string|max:20',
            'settings.header_text'       => 'nullable|string|max:100',
            'settings.launcher_icon'     => 'nullable|in:bubble,robot,heart',
            'settings.position'          => 'nullable|in:bottom-right,bottom-left',
            'settings.window_width'      => 'nullable|integer|min:280|max:600',
            'settings.window_height'     => 'nullable|integer|min:400|max:800',
            'settings.font_size'         => 'nullable|integer|min:11|max:20',
            'settings.user_bubble_color' => 'nullable|string|max:20',
            'settings.bot_bubble_color'  => 'nullable|string|max:20',
            'settings.user_text_color'   => 'nullable|string|max:20',
            'settings.bot_text_color'    => 'nullable|string|max:20',
            'settings.show_powered_by'   => 'nullable|boolean',
            'settings.initial_open'      => 'nullable|boolean',
        ]);

        $settings = $request->input('settings', []);
        $settings['show_powered_by'] = $request->boolean('settings.show_powered_by');
        $settings['initial_open']    = $request->boolean('settings.initial_open');

        $chatbot->update([
            'name'            => $data['name'],
            'description'     => $data['description'] ?? $chatbot->description,
            'ai_provider'     => $data['ai_provider'],
            'ai_model'        => $data['ai_model'] ?? $chatbot->ai_model,
            'system_prompt'   => $data['system_prompt'] ?? $chatbot->system_prompt,
            'welcome_message' => $data['welcome_message'] ?? $chatbot->welcome_message,
            'fallback_message'=> $data['fallback_message'] ?? $chatbot->fallback_message,
            'temperature'     => $data['temperature'] ?? $chatbot->temperature,
            'max_tokens'      => $data['max_tokens'] ?? $chatbot->max_tokens,
            'status'          => $data['status'] ?? $chatbot->status,
            'settings'        => $settings,
            'updated_by_id'   => auth()->id(),
        ]);

        return redirect()->route('titanagents.chatbot.builder', $chatbot)
            ->with('success', 'Chatbot settings saved.');
    }
}
