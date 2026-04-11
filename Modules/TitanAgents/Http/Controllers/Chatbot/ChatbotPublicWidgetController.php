<?php

namespace Modules\TitanAgents\Http\Controllers\Chatbot;

use App\Http\Controllers\Controller;
use Modules\TitanAgents\Models\Chatbot;

class ChatbotPublicWidgetController extends Controller
{
    /**
     * Serve the full-page widget (loaded in an iframe on client sites).
     */
    public function frame(string $chatbotId)
    {
        $chatbot = Chatbot::where('id', $chatbotId)->where('status', 'active')->firstOrFail();

        $defaults = [
            'theme_color'       => '#6366f1',
            'header_text'       => $chatbot->name,
            'launcher_icon'     => 'bubble',
            'position'          => 'bottom-right',
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

        return response()
            ->view('titanagents::chatbot.widget.frame', compact('chatbot', 'settings'))
            ->header('Content-Security-Policy', 'frame-ancestors *');
    }
}
