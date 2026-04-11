<?php

namespace Modules\TitanAgents\Http\Controllers\Chatbot;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\TitanAgents\Models\Chatbot;
use Modules\TitanAgents\Services\Chatbot\ChatbotAnalyticsService;
use Modules\TitanAgents\Services\Chatbot\ConversationExportService;

class ChatbotAnalyticsController extends AccountBaseController
{
    public function __construct(
        protected ChatbotAnalyticsService $analyticsService,
        protected ConversationExportService $exportService
    ) {
        parent::__construct();
    }

    public function index(Chatbot $chatbot, Request $request)
    {
        $period    = $request->input('period', '30d');
        $summary   = $this->analyticsService->getSummary($chatbot, $period);
        $dailyData = $this->analyticsService->getConversationsByDay($chatbot);

        return view('titanagents::chatbot.analytics.index', compact('chatbot', 'summary', 'dailyData', 'period'));
    }

    public function export(Chatbot $chatbot, Request $request)
    {
        $format  = $request->input('format', 'csv');
        $filters = $request->only(['status', 'from', 'to']);

        if ($format === 'json') {
            return response($this->exportService->exportToJson($chatbot, $filters))
                ->header('Content-Type', 'application/json')
                ->header('Content-Disposition', "attachment; filename=conversations-{$chatbot->id}.json");
        }

        return response($this->exportService->exportToCsv($chatbot, $filters))
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=conversations-{$chatbot->id}.csv");
    }
}
