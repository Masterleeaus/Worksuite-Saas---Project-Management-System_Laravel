<?php

namespace Modules\TitanAgents\Http\Controllers\Voice;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\TitanAgents\Http\Requests\Voice\VoiceAvatarRequest;
use Modules\TitanAgents\Http\Resources\Voice\ChatbotAvatarResource;
use Modules\TitanAgents\Models\Voice\VoiceChatbotAvatar;

class VoiceAvatarController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __invoke(VoiceAvatarRequest $request): JsonResource|JsonResponse
    {
        if (config('app.demo')) {
            return response()->json([
                'type'    => 'error',
                'message' => __('This feature is disabled in Demo version.'),
            ], 403);
        }

        $file = $request->file('avatar')->store('avatars', ['disk' => 'public']);

        $chatbotAvatar = VoiceChatbotAvatar::query()->create([
            'user_id' => $request->user()->getAttribute('id'),
            'avatar'  => 'uploads/' . $file,
        ]);

        return ChatbotAvatarResource::make($chatbotAvatar);
    }
}
