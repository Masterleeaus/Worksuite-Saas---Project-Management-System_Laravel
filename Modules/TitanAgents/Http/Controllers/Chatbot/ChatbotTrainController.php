<?php

namespace Modules\TitanAgents\Http\Controllers\Chatbot;

use App\Http\Controllers\AccountBaseController;
use Modules\TitanAgents\Models\Chatbot;
use Modules\TitanAgents\Services\Chatbot\TrainingService;

class ChatbotTrainController extends AccountBaseController
{
    public function __construct(protected TrainingService $trainingService)
    {
        parent::__construct();
    }

    public function index(Chatbot $chatbot)
    {
        $articles = $chatbot->articles()->latest()->get();

        return view('titanagents::chatbot.train.index', compact('chatbot', 'articles'));
    }

    public function trainAll(Chatbot $chatbot)
    {
        try {
            $results = $this->trainingService->trainAll($chatbot);

            return back()->with('success', "Training complete: {$results['success']} succeeded, {$results['failed']} failed.");
        } catch (\Throwable $e) {
            return back()->with('error', 'Training failed: ' . $e->getMessage());
        }
    }

    public function retrainAll(Chatbot $chatbot)
    {
        try {
            $results = $this->trainingService->retrainAll($chatbot);

            return back()->with('success', "Retraining complete: {$results['success']} succeeded, {$results['failed']} failed.");
        } catch (\Throwable $e) {
            return back()->with('error', 'Retraining failed: ' . $e->getMessage());
        }
    }
}
