<?php

namespace Modules\TitanAgents\Services\Voice;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\TitanAgents\Models\Voice\VoiceChatbot;
use Modules\TitanAgents\Models\Voice\VoiceChatbotAvatar;
use Modules\TitanAgents\Models\Voice\VoiceChatbotTrain;

class VoiceChatbotService
{
    public function __construct(protected ElevenLabsVoiceService $elevenlabs) {}

    public function query(): Builder
    {
        return VoiceChatbot::query();
    }

    public function avatars(): Collection|array
    {
        return VoiceChatbotAvatar::query()
            ->where(function (Builder $query) {
                return $query->where('user_id', Auth::id())->orWhereNull('user_id');
            })
            ->get();
    }

    public function createAgent(array $args): JsonResponse
    {
        $conversation_config = [
            'agent' => [
                'first_message' => $args['welcome_message'],
                'prompt'        => [
                    'prompt' => $args['instructions'],
                ],
            ],
            'tts' => [
                'model_id' => ElevenLabsVoiceService::DEFAULT_ELEVENLABS_MODEL_FOR_ENGLISH,
            ],
        ];

        if (! empty($args['language']) && $args['language'] !== 'auto') {
            $conversation_config['agent']['language'] = $args['language'];
            if ($args['language'] !== 'en') {
                $conversation_config['tts']['model_id'] = ElevenLabsVoiceService::DEFAULT_ELEVENLABS_MODEL;
            }
        }

        return $this->elevenlabs->createAgent(
            conversation_config: $conversation_config,
            name: $args['title']
        );
    }

    public function updateAgent(int|string $chatbot_id): ?JsonResponse
    {
        $agent = VoiceChatbot::find($chatbot_id);
        if (empty($agent)) {
            return null;
        }

        $conversation_config = [
            'agent' => [
                'first_message' => $agent->welcome_message,
                'prompt'        => [
                    'prompt' => $agent->instructions,
                ],
            ],
            'tts' => [
                'voice_id' => $agent->voice_id,
                'model_id' => ElevenLabsVoiceService::DEFAULT_ELEVENLABS_MODEL_FOR_ENGLISH,
            ],
        ];

        if (! empty($agent->language) && $agent->language !== 'auto') {
            $conversation_config['agent']['language'] = $agent->language;
            if ($agent->language !== 'en') {
                $conversation_config['tts']['model_id'] = ElevenLabsVoiceService::DEFAULT_ELEVENLABS_MODEL;
            }
        }

        $this->updateAgentWithKnowledgebase($chatbot_id);

        return $this->elevenlabs->updateAgent(
            agent_id: $agent->agent_id,
            conversation_config: $conversation_config,
            name: $agent->title
        );
    }

    public function deleteAgent(string|int $agent_id): JsonResponse
    {
        return $this->elevenlabs->deleteAgent($agent_id);
    }

    public function getVoices(): array
    {
        $res = $this->elevenlabs->getListOfVoices(page_size: 100);
        if ($res->getData()->status === 'success') {
            return (array) ($res->getData()->resData->voices ?? []);
        }

        return [];
    }

    public function addKnowledgebase(string $type, mixed $content, ?string $name = null): JsonResponse
    {
        return match ($type) {
            'text' => $this->elevenlabs->createKnowledgebaseDocFromText(text: $content, name: $name),
            'url'  => $this->elevenlabs->createKnowledgebaseDocFromUrl(url: $content, name: $name),
            'file' => $this->elevenlabs->createKnowledgebaseDocFromFile(file: $content, name: $name),
        };
    }

    public function deleteKnowledgebase(string $doc_id): JsonResponse
    {
        return $this->elevenlabs->deleteKnowledgebaseDocument($doc_id);
    }

    public function updateAgentWithKnowledgebase(int|string $chatbot_id): void
    {
        $trains = VoiceChatbotTrain::query()
            ->whereNotNull('trained_at')
            ->whereNotNull('doc_id')
            ->where('chatbot_id', $chatbot_id)
            ->get();

        $trainedKnowledges = $trains->map(fn ($train) => [
            'id'   => $train->doc_id,
            'name' => $train->name,
            'type' => $train->type,
        ])->all();

        if (empty($trainedKnowledges)) {
            return;
        }

        $config = [
            'agent' => [
                'prompt' => [
                    'knowledge_base' => $trainedKnowledges,
                ],
            ],
        ];

        $agent_id = VoiceChatbot::findOrFail($chatbot_id)?->agent_id;

        if ($agent_id) {
            $this->elevenlabs->updateAgent(agent_id: $agent_id, conversation_config: $config);
        }
    }
}
