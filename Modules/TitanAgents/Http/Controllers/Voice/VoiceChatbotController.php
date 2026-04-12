<?php

namespace Modules\TitanAgents\Http\Controllers\Voice;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Modules\TitanAgents\Http\Requests\Voice\VoiceChatbotStoreRequest;
use Modules\TitanAgents\Http\Requests\Voice\VoiceChatbotUpdateRequest;
use Modules\TitanAgents\Models\Voice\VoiceChatbot;
use Modules\TitanAgents\Services\Voice\ElevenLabsVoiceService;
use Modules\TitanAgents\Services\Voice\VoiceChatbotService;

class VoiceChatbotController extends AccountBaseController
{
    public function __construct(public VoiceChatbotService $service)
    {
        parent::__construct();
    }

    public function index(): View
    {
        $chatbots = $this->service->query()
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(perPage: 100);

        return view('titanagents::voice.index', [
            'chatbots' => $chatbots,
            'avatars'  => $this->service->avatars(),
            'voices'   => $this->service->getVoices(),
        ]);
    }

    public function store(VoiceChatbotStoreRequest $request): JsonResource|JsonResponse
    {
        if (config('app.demo')) {
            return response()->json([
                'type'    => 'error',
                'message' => __('This feature is disabled in Demo version.'),
            ], 403);
        }

        $reqData = $request->validated();

        if (isset($reqData['language']) && $reqData['language'] === 'auto') {
            unset($reqData['language']);
        }

        $res = $this->service->createAgent($reqData);

        if ($res->getData()->status === 'success') {
            $reqData['agent_id'] = $res->getData()->resData->agent_id;
            $reqData['voice_id'] = ElevenLabsVoiceService::DEFAULT_ELEVENLABS_VOICE_ID;
            $reqData['ai_model'] = ElevenLabsVoiceService::DEFAULT_ELEVENLABS_MODEL;

            if (! empty($reqData['language']) && $reqData['language'] === 'en') {
                $reqData['ai_model'] = ElevenLabsVoiceService::DEFAULT_ELEVENLABS_MODEL_FOR_ENGLISH;
            }

            $chatbot = VoiceChatbot::create($reqData);

            return JsonResource::make($chatbot);
        }

        return $res->setStatusCode(422);
    }

    public function update(VoiceChatbotUpdateRequest $request): JsonResource|JsonResponse
    {
        if (config('app.demo')) {
            return response()->json([
                'type'    => 'error',
                'message' => __('This feature is disabled in Demo version.'),
            ], 403);
        }

        $reqData = $request->validated();

        if (isset($reqData['language']) && $reqData['language'] === 'auto') {
            unset($reqData['language']);
        }

        $chatbot = VoiceChatbot::findOrFail($reqData['id']);
        $chatbot->update($reqData);

        $this->service->updateAgent($chatbot->id);

        return JsonResource::make($chatbot->fresh());
    }

    public function delete(Request $request): JsonResponse
    {
        if (config('app.demo')) {
            return response()->json([
                'type'    => 'error',
                'message' => __('This feature is disabled in Demo version.'),
            ], 403);
        }

        $request->validate(['id' => 'required']);

        $chatbot = $this->service->query()->findOrFail($request->get('id'));

        if ($chatbot->getAttribute('user_id') === Auth::id()) {
            $this->service->deleteAgent($chatbot->agent_id);
            $chatbot->delete();
        } else {
            abort(403);
        }

        return response()->json([
            'message' => __('Voice Chatbot deleted successfully'),
            'type'    => 'success',
            'status'  => 200,
        ]);
    }

    public function frame(string $uuid): View|Response
    {
        $chatbot = VoiceChatbot::whereUuid($uuid)->firstOrFail();

        return view('titanagents::voice.frame', compact('chatbot'));
    }

    public function checkVoiceBalance(Request $request): ?JsonResponse
    {
        if (config('app.demo')) {
            $clientIp = $request->ip();
            $key      = 'voice-chat-attempt:' . $clientIp;
            $executed = RateLimiter::attempt($key, 25, fn () => true, 60);

            if ($executed) {
                return response()->json(['status' => 'success', 'message' => 'Demo mode'], 200);
            }

            return response()->json(['status' => 'error', 'message' => 'Exceeded messages limit on demo'], 200);
        }

        $uuId   = $request->input('uuId');
        $chatbot = VoiceChatbot::whereUuid($uuId)->first();

        if (! empty($chatbot)) {
            // If the Entity credit system exists (optional integration), check balance
            if (class_exists(\App\Domains\Entity\Facades\Entity::class)) {
                try {
                    $entityEnum = constant('\App\Domains\Entity\Enums\EntityEnum::ELEVENLABS_VOICE_CHATBOT');
                    $driver = \App\Domains\Entity\Facades\Entity::driver($entityEnum)->forUser($chatbot->user);
                    $driver->redirectIfNoCreditBalance();
                } catch (\Exception $e) {
                    return response()->json([
                        'message' => $e->getMessage(),
                        'status'  => 'error',
                    ], 200);
                }
            }
        }

        return response()->json(['status' => 'success', 'message' => ''], 200);
    }
}
