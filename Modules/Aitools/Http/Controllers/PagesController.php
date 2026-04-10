<?php

namespace Modules\Aitools\Http\Controllers;

use Illuminate\Routing\Controller;

class PagesController extends Controller
{
    public function chatUi()
    {
        return view('aitools::pages.chat-ui');
    }

    public function conversations()
    {
        return view('aitools::pages.conversations');
    }

    public function conversationShow(int $conversation)
    {
        return view('aitools::pages.conversation-show', ['conversation_id' => $conversation]);
    }

    public function toolsUi()
    {
        return view('aitools::pages.tools-ui');
    }

    public function insights()
    {
        return view('aitools::pages.insights');
    }

    public function pulse()
    {
        return view('aitools::pages.pulse');
    }

    public function signals()
    {
        return view('aitools::pages.signals');
    }

    public function settings()
    {
        return view('aitools::pages.settings');
    }

    public function diagnostics()
    {
        return view('aitools::pages.diagnostics');
    }
}
