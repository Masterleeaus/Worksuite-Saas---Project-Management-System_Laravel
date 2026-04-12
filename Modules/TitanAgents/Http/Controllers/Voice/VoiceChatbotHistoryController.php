<?php

namespace Modules\TitanAgents\Http\Controllers\Voice;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\TitanAgents\Http\Requests\Voice\VoiceChatHistoryStoreRequest;
use Modules\TitanAgents\Http\Resources\Voice\ChatbotConversationHistoryResource;
use Modules\TitanAgents\Models\Voice\VoiceChatbot;
use Modules\TitanAgents\Models\Voice\VoiceChatbotConversation;
use Modules\TitanAgents\Services\Voice\ElevenLabsVoiceService;
use Throwable;

class VoiceChatbotHistoryController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function loadConversationWithPaginate(): AnonymousResourceCollection
    {
        $voiceChatbots = VoiceChatbot::where('user_id', auth()->id())
            ->pluck('uuid')
            ->toArray();

        $inProgressConvs = VoiceChatbotConversation::whereIn('status', ['in-progress', 'processing'])
            ->whereIn('chatbot_uuid', $voiceChatbots)
            ->get();

        foreach ($inProgressConvs as $conv) {
            static::storeTranscripts($conv);
        }

        return ChatbotConversationHistoryResource::collection(
            VoiceChatbotConversation::where('status', 'done')
                ->whereIn('chatbot_uuid', $voiceChatbots)
                ->with('chat_histories')
                ->paginate()
        );
    }

    public function storeConversation(string $uuid, VoiceChatHistoryStoreRequest $request): JsonResponse
    {
        $chatbot = VoiceChatbot::whereUuid($uuid)->first();

        if (empty($chatbot)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid uuid',
            ], 404);
        }

        try {
            $conversationId = $request->validated()['conversation_id'];

            $conversation = $chatbot->conversations()
                ->where('conversation_id', $conversationId)
                ->first();

            if (! $conversation) {
                $conversation = $chatbot->conversations()->create([
                    'conversation_id' => $conversationId,
                ]);
            } else {
                $conversation->chat_histories()->delete();
            }

            static::storeTranscripts($conversation);

            return response()->json(['status' => 'success']);
        } catch (Throwable $th) {
            return response()->json([
                'status'  => 'error',
                'message' => 'create conversation failed',
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public static function storeTranscripts(VoiceChatbotConversation $conversation): void
    {
        $service = new ElevenLabsVoiceService;
        $res     = $service->getConversationDetail($conversation->conversation_id);

        // Optional Entity credit usage — guarded with class_exists
        $chatbotUUID = $conversation->chatbot_uuid;
        $chatbot     = VoiceChatbot::whereUuid($chatbotUUID)->first();

        if (! empty($chatbot) && class_exists(\App\Domains\Entity\Facades\Entity::class)) {
            try {
                $cost = data_get($res->original, 'resData.metadata.cost', 0);
                $chars  = Str::random((int) $cost);
                $user   = $chatbot->user;
                $entityEnum = constant('\App\Domains\Entity\Enums\EntityEnum::ELEVENLABS_VOICE_CHATBOT');
                $driver = \App\Domains\Entity\Facades\Entity::driver($entityEnum)->forUser($user);
                $driver->input($chars)->calculateCredit()->decreaseCredit();

                if (class_exists(\App\Models\Usage::class)) {
                    \App\Models\Usage::getSingle()->updateWordCounts($driver->calculate());
                }
            } catch (Throwable $th) {
                Log::warning('[VoiceChatbotHistory] Entity credit tracking failed: ' . $th->getMessage());
            }
        }

        if ($res->getData()->status === 'error') {
            return;
        }

        try {
            $resData = $res->getData()->resData;

            $conversation->status = $resData->status;
            $conversation->save();

            if (! in_array($resData->status, ['in-progress', 'processing'], true)) {
                $transcripts = $resData->transcript;

                foreach ($transcripts as $transcript) {
                    $conversation->chat_histories()->create([
                        'role'    => $transcript->role,
                        'message' => $transcript->message,
                    ]);
                }
            }
        } catch (Throwable $th) {
            Log::error('[VoiceChatbotHistory] store chat history error: ' . $th->getMessage());
        }
    }
}
