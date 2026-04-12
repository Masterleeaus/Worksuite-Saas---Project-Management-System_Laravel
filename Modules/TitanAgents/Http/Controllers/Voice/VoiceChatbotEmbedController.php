<?php

namespace Modules\TitanAgents\Http\Controllers\Voice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\TitanAgents\Models\Voice\VoiceChatbot;

class VoiceChatbotEmbedController extends Controller
{
    /**
     * Return voice chatbot configuration by UUID (used by the embed widget).
     */
    public function index(string $uuid): JsonResource
    {
        $chatbot = VoiceChatbot::where('uuid', $uuid)->first();

        return JsonResource::make($chatbot);
    }
}
