<?php

namespace Modules\TitanAgents\Http\Controllers\Voice;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Modules\TitanAgents\Enums\Voice\TrainTypeEnum;
use Modules\TitanAgents\Http\Requests\Voice\Train\DataRequest;
use Modules\TitanAgents\Http\Requests\Voice\Train\FileRequest;
use Modules\TitanAgents\Http\Requests\Voice\Train\TextRequest;
use Modules\TitanAgents\Http\Requests\Voice\Train\TrainRequest;
use Modules\TitanAgents\Http\Requests\Voice\Train\TrainUrlRequest;
use Modules\TitanAgents\Http\Resources\Voice\ChatbotTrainResource;
use Modules\TitanAgents\Models\Voice\VoiceChatbotTrain;
use Modules\TitanAgents\Services\Voice\LinkParser;
use Modules\TitanAgents\Services\Voice\VoiceChatbotService;

class VoiceChatbotTrainController extends AccountBaseController
{
    public function __construct(public VoiceChatbotService $service)
    {
        parent::__construct();
    }

    public function trainData(DataRequest $request): AnonymousResourceCollection
    {
        return ChatbotTrainResource::collection(
            $this->service->query()
                ->findOrFail($request->validated('id'))
                ->trains()
                ->when($request->validated('type'), fn ($query) => $query->where('type', $request->validated('type')))
                ->get()
        );
    }

    public function delete(TrainRequest $request): JsonResponse
    {
        $chatbot = $this->service->query()->findOrFail($request->validated('id'));

        $trains = $chatbot->trains()->whereIn('id', $request->validated('data'))->get();

        foreach ($trains as $train) {
            if ($train->trained_at && $train->doc_id) {
                $train->trained_at = null;
                $train->save();

                $this->service->updateAgentWithKnowledgebase($train->chatbot_id);
                $this->service->deleteKnowledgebase($train->doc_id);
            }
            $train->delete();
        }

        return response()->json([
            'message' => __('Deleted successfully'),
            'status'  => 200,
        ]);
    }

    public function trainFile(FileRequest $request): JsonResponse|AnonymousResourceCollection
    {
        if (config('app.demo')) {
            return response()->json([
                'type'    => 'error',
                'message' => __('This feature is disabled in Demo version.'),
            ], 403);
        }

        $chatbot = $this->service->query()->findOrFail($request->validated('id'));

        /** @var \Illuminate\Http\UploadedFile $file */
        $file = $request->file('file');
        $path = $file->store('voice-chatbot', ['disk' => 'public']);

        VoiceChatbotTrain::create([
            'type'       => TrainTypeEnum::file,
            'name'       => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'user_id'    => Auth::id(),
            'chatbot_id' => $chatbot->getKey(),
            'file'       => $path,
        ]);

        return ChatbotTrainResource::collection(
            $chatbot->trains()->whereNotNull('file')->get()
        );
    }

    public function trainText(TextRequest $request): JsonResponse|AnonymousResourceCollection
    {
        if (config('app.demo')) {
            return response()->json([
                'type'    => 'error',
                'message' => __('This feature is disabled in Demo version.'),
            ], 403);
        }

        $chatbot = $this->service->query()->findOrFail($request->validated('id'));

        VoiceChatbotTrain::create([
            'type'       => TrainTypeEnum::text,
            'user_id'    => Auth::id(),
            'chatbot_id' => $chatbot->getKey(),
            'name'       => $request->validated('title'),
            'text'       => $request->validated('content'),
        ]);

        return ChatbotTrainResource::collection(
            $chatbot->trains()
                ->whereNull('file')
                ->whereNull('url')
                ->get()
        );
    }

    public function trainUrl(TrainUrlRequest $request): JsonResponse|AnonymousResourceCollection
    {
        if (config('app.demo')) {
            return response()->json([
                'type'    => 'error',
                'message' => __('This feature is disabled in Demo version.'),
            ], 403);
        }

        $chatbot = $this->service->query()->findOrFail($request->validated('id'));

        app(LinkParser::class)
            ->setBaseUrl($request->validated('url'))
            ->crawl((bool) $request->validated('single'))
            ->insertEmbeddings($chatbot);

        return ChatbotTrainResource::collection(
            $chatbot->trains()->whereNotNull('url')->get()
        );
    }

    public function generateEmbedding(TrainRequest $request): JsonResponse|AnonymousResourceCollection
    {
        if (config('app.demo')) {
            return response()->json([
                'type'    => 'error',
                'message' => __('This feature is disabled in Demo version.'),
            ], 403);
        }

        $chatbot = $this->service->query()->findOrFail($request->validated('id'));

        ini_set('max_execution_time', 300);

        $data   = $request->validated('data');
        $trains = VoiceChatbotTrain::query()
            ->whereNull('trained_at')
            ->whereIn('id', $data)
            ->get();

        foreach ($trains as $train) {
            $content = '';

            if ($train->type === 'text') {
                $content = $train->text;
            } elseif ($train->type === 'url') {
                $content = $train->url;
            } elseif ($train->type === 'file') {
                $storagePath = storage_path('app/public/' . $train->file);
                $content     = new UploadedFile(
                    $storagePath,
                    basename($storagePath),
                    mime_content_type($storagePath) ?: null,
                    null,
                    true
                );
            }

            $res = $this->service->addKnowledgebase($train->type, $content, $train->name);

            if ($res->getData()->status === 'success') {
                $train->update([
                    'name'       => $res->getData()->resData?->name,
                    'doc_id'     => $res->getData()->resData?->id,
                    'trained_at' => now(),
                ]);
            }
        }

        if ($trains->isNotEmpty()) {
            $this->service->updateAgentWithKnowledgebase($trains->first()->chatbot_id);
        }

        return ChatbotTrainResource::collection($chatbot->trains()->whereIn('id', $data)->get());
    }
}
