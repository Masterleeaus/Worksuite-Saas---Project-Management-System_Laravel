<?php

namespace Modules\TitanReach\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TitanReach\Services\InboxAggregatorService;
use Modules\TitanReach\Services\ReachAiService;

class InboxController extends Controller
{
    public function __construct(
        protected InboxAggregatorService $inbox,
        protected ReachAiService         $ai,
    ) {}

    public function index(Request $request)
    {
        $companyId = auth()->user()?->company_id ?? 0;

        $filters = $request->only(['channel', 'status', 'search', 'assigned_to']);
        $conversations = $this->inbox->getConversations($companyId, $filters);

        return view('titanreach::inbox.index', compact('conversations', 'filters'));
    }

    public function show(int $id)
    {
        $conversation = $this->inbox->getConversation($id);

        if (!$conversation) {
            abort(404);
        }

        $this->inbox->markAsRead($id);

        return view('titanreach::inbox.show', compact('conversation'));
    }

    public function assign(Request $request, int $id)
    {
        $request->validate(['user_id' => 'required|integer']);
        $this->inbox->assignTo($id, (int) $request->input('user_id'));

        return back()->with('success', 'Conversation assigned.');
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate(['status' => 'required|in:open,closed,pending,spam']);
        $this->inbox->updateStatus($id, $request->input('status'));

        return back()->with('success', 'Status updated.');
    }

    public function suggestReply(int $id)
    {
        $suggestion = $this->ai->suggestReply($id);

        return response()->json(['suggestion' => $suggestion]);
    }
}
