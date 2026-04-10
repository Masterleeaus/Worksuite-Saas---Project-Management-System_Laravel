<?php

namespace Modules\CustomerFeedback\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AccountBaseController;
use Modules\CustomerFeedback\Entities\FeedbackTicket;
use Modules\CustomerFeedback\Entities\FeedbackInsight;
use Modules\CustomerFeedback\Services\FeedbackAiService;

class FeedbackInsightsController extends AccountBaseController
{
    protected $aiService;

    public function __construct(FeedbackAiService $aiService)
    {
        parent::__construct();
        $this->aiService = $aiService;
        $this->pageTitle = 'customer-feedback::modules.insights';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('customer-feedback', $this->user->modules));
            return $next($request);
        });
    }

    /**
     * Get insights for a ticket
     */
    public function getTicketInsights(FeedbackTicket $ticket)
    {
        $insights = $ticket->insights()->orderBy('created_at', 'desc')->get();
        return response()->json(['data' => $insights]);
    }

    /**
     * Analyze ticket with AI
     */
    public function analyzeTicket(FeedbackTicket $ticket)
    {
        $insights = $this->aiService->analyzeTicket($ticket);
        return response()->json(['insights' => $insights]);
    }

    /**
     * Get sentiment analysis
     */
    public function getSentiment(FeedbackTicket $ticket)
    {
        $sentiment = $this->aiService->analyzeSentiment($ticket->description);
        return response()->json(['sentiment' => $sentiment]);
    }

    /**
     * Get category suggestion
     */
    public function getSuggestedCategory(FeedbackTicket $ticket)
    {
        $category = $this->aiService->suggestCategory($ticket->description);
        return response()->json(['suggested_category' => $category]);
    }

    /**
     * Get priority suggestion
     */
    public function getSuggestedPriority(FeedbackTicket $ticket)
    {
        $priority = $this->aiService->suggestPriority($ticket);
        return response()->json(['suggested_priority' => $priority]);
    }

    /**
     * Get suggested response template
     */
    public function getSuggestedResponse(FeedbackTicket $ticket)
    {
        $response = $this->aiService->suggestResponse($ticket);
        return response()->json(['suggested_response' => $response]);
    }

    /**
     * View insights dashboard
     */
    public function dashboard()
    {
        $this->insightSummary = FeedbackInsight::selectRaw('insight_type, COUNT(*) as count')
            ->groupBy('insight_type')
            ->get();

        $this->recentInsights = FeedbackInsight::with('ticket')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $this->highConfidenceInsights = FeedbackInsight::where('confidence_score', '>=', 0.85)
            ->with('ticket')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('customer-feedback::insights.dashboard', $this->data);
    }
}
