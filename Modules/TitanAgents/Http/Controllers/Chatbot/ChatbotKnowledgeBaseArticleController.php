<?php

namespace Modules\TitanAgents\Http\Controllers\Chatbot;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\TitanAgents\Models\Chatbot;
use Modules\TitanAgents\Models\ChatbotKnowledgeBaseArticle;

class ChatbotKnowledgeBaseArticleController extends AccountBaseController
{
    public function index(Chatbot $chatbot)
    {
        $articles = $chatbot->articles()->latest()->paginate(20);

        return view('titanagents::chatbot.kb.index', compact('chatbot', 'articles'));
    }

    public function store(Request $request, Chatbot $chatbot)
    {
        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'content'  => 'required|string',
            'category' => 'nullable|string|max:100',
        ]);

        $data['chatbot_id']    = $chatbot->id;
        $data['created_by_id'] = auth()->id();

        ChatbotKnowledgeBaseArticle::create($data);

        return back()->with('success', __('Article created.'));
    }

    public function update(Request $request, Chatbot $chatbot, ChatbotKnowledgeBaseArticle $article)
    {
        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'content'  => 'required|string',
            'category' => 'nullable|string|max:100',
            'status'   => 'in:active,inactive',
        ]);

        $data['updated_by_id']    = auth()->id();
        $data['embedding_status'] = 'pending';

        $article->update($data);

        return back()->with('success', __('Article updated.'));
    }

    public function destroy(Chatbot $chatbot, ChatbotKnowledgeBaseArticle $article)
    {
        $article->delete();

        return back()->with('success', __('Article deleted.'));
    }
}
