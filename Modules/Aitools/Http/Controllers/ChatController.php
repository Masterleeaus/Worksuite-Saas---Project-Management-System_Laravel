<?php

namespace Modules\Aitools\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Aitools\Entities\AiToolsConversation;
use Modules\Aitools\Entities\AiToolsMessage;
use Modules\Aitools\Services\Chat\ChatOrchestrator;

class ChatController extends Controller
{
    public function __construct(protected ChatOrchestrator $orchestrator)
    {
    }

    /**
     * POST /account/aitools/chat
     */
    public function chat(Request $request): JsonResponse
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:4000'],
            'conversation_id' => ['nullable', 'integer'],
            'meta' => ['nullable', 'array'],
        ]);

        $res = $this->orchestrator->handle(
            (string) $data['message'],
            $data['conversation_id'] ?? null,
            $data['meta'] ?? []
        );

        if (!($res['success'] ?? false)) {
            return response()->json([
                'success' => false,
                'message' => $res['message'] ?? __('Unable to process request.'),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'conversation_id' => $res['conversation_id'] ?? null,
            'reply' => $res['reply'] ?? '',
            'tools' => $res['tools'] ?? [],
        ]);
    }

    
    /**
     * GET /account/aitools/conversations
     * Returns a lightweight list of recent conversations for the current user/company.
     */
    public function list(): JsonResponse
    {
        $companyId = null;
        try {
            if (function_exists('company') && company()) {
                $companyId = (int) company()->id;
            } elseif (auth()->user() && isset(auth()->user()->company_id)) {
                $companyId = (int) auth()->user()->company_id;
            }
        } catch (\Throwable $e) {
            $companyId = null;
        }

        $q = AiToolsConversation::query()
            ->orderByDesc('id')
            ->limit(50);

        if ($companyId !== null) {
            $q->where(function ($sub) use ($companyId) {
                $sub->whereNull('company_id')->orWhere('company_id', $companyId);
            });
        }

        if (auth()->id()) {
            $q->where(function ($sub) {
                $sub->whereNull('user_id')->orWhere('user_id', auth()->id());
            });
        }

        $convos = $q->get(['id','title','channel','status','created_at']);

        return response()->json(['success' => true, 'conversations' => $convos]);
    }


    /**
     * GET /account/aitools/chat/{conversation}
     */
    public function history(int $conversation): JsonResponse
    {
        $companyId = null;
        try {
            if (function_exists('company') && company()) {
                $companyId = (int) company()->id;
            } elseif (auth()->user() && isset(auth()->user()->company_id)) {
                $companyId = (int) auth()->user()->company_id;
            }
        } catch (\Throwable $e) {
            $companyId = null;
        }

        $q = AiToolsConversation::query()->where('id', $conversation);
        if ($companyId !== null) {
            $q->where(function ($sub) use ($companyId) {
                $sub->whereNull('company_id')->orWhere('company_id', $companyId);
            });
        }

        $conv = $q->first();
        if (!$conv) {
            return response()->json(['success' => false, 'message' => __('Conversation not found.')], 404);
        }

        $messages = AiToolsMessage::query()
            ->where('conversation_id', $conv->id)
            ->orderBy('id')
            ->get(['id','role','content','created_at','meta']);

        return response()->json([
            'success' => true,
            'conversation' => [
                'id' => $conv->id,
                'title' => $conv->title,
                'channel' => $conv->channel,
                'status' => $conv->status,
                'created_at' => $conv->created_at,
            ],
            'messages' => $messages,
        ]);
    }
}
