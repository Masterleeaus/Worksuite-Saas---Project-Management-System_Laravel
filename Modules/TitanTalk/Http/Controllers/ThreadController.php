<?php

namespace Modules\TitanTalk\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\TitanTalk\Models\TitanTalkMessage;

class ThreadController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(TitanTalkMessage $message): JsonResponse
    {
        if ($message->company_id !== company()->id) {
            abort(403);
        }

        $replies = $message->threadReplies()
            ->with(['author', 'files', 'reactions'])
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'parent'  => $message->load('author'),
            'replies' => $replies,
        ]);
    }

    public function store(Request $request, TitanTalkMessage $message): JsonResponse
    {
        if ($message->company_id !== company()->id) {
            abort(403);
        }

        $request->validate(['body' => 'required|string|max:10000']);

        $reply = TitanTalkMessage::create([
            'company_id'       => company()->id,
            'room_id'          => $message->room_id,
            'user_id'          => user()->id,
            'body'             => $request->body,
            'thread_parent_id' => $message->id,
        ]);

        $message->increment('thread_reply_count');

        $reply->load(['author', 'files', 'reactions']);

        return response()->json(['status' => 'success', 'reply' => $reply], 201);
    }
}
