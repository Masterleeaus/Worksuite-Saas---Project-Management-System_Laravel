<?php

namespace Modules\CustomerFeedback\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\AccountBaseController;
use Modules\CustomerFeedback\Entities\FeedbackTicket;
use Modules\CustomerFeedback\Entities\NpsSurvey;

class AnalyticsController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'customer-feedback::modules.analytics';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('customer-feedback', $this->user->modules));
            return $next($request);
        });
    }

    /**
     * Main analytics dashboard
     */
    public function dashboard()
    {
        $startDate = request()->start_date ? Carbon::parse(request()->start_date) : now()->subDays(30);
        $endDate   = request()->end_date   ? Carbon::parse(request()->end_date)   : now();

        $this->totalTickets          = FeedbackTicket::whereBetween('created_at', [$startDate, $endDate])->count();
        $this->openTickets           = FeedbackTicket::whereBetween('created_at', [$startDate, $endDate])->unresolved()->count();
        $this->averageResolutionTime = $this->getAverageResolutionTime($startDate, $endDate);
        $this->satisfactionScore     = $this->getAverageNpsScore($startDate, $endDate);

        $this->ticketsOverTime  = $this->getTicketsOverTime($startDate, $endDate);
        $this->statusBreakdown  = $this->getStatusBreakdown();
        $this->priorityBreakdown = $this->getPriorityBreakdown();
        $this->typeBreakdown    = $this->getFeedbackTypeBreakdown();
        $this->topAgents        = $this->getTopAgents(5);
        $this->npsChart         = $this->getNpsData($startDate, $endDate);

        return view('customer-feedback::analytics.dashboard', $this->data);
    }

    /**
     * NPS analytics using completed nps_surveys
     */
    public function nps()
    {
        $startDate = request()->start_date ? Carbon::parse(request()->start_date) : now()->subDays(90);
        $endDate   = request()->end_date   ? Carbon::parse(request()->end_date)   : now();

        $completed = NpsSurvey::whereNotNull('completed_at')
            ->whereNotNull('nps_score')
            ->whereBetween('completed_at', [$startDate, $endDate])
            ->get();

        $total = $completed->count();

        $this->promoters   = $total > 0 ? $completed->where('nps_score', '>=', 9)->count() : 0;
        $this->passives    = $total > 0 ? $completed->whereBetween('nps_score', [7, 8])->count() : 0;
        $this->detractors  = $total > 0 ? $completed->where('nps_score', '<=', 6)->count() : 0;
        $this->npsScore    = $total > 0
            ? round((($this->promoters - $this->detractors) / $total) * 100, 1)
            : null;

        $this->scoresOverTime     = $this->getNpsScoresOverTime($startDate, $endDate);
        $this->scoreDistribution  = $this->getNpsScoreDistribution();
        $this->recentComments     = NpsSurvey::whereNotNull('comments')
            ->whereNotNull('completed_at')
            ->whereBetween('completed_at', [$startDate, $endDate])
            ->orderByDesc('completed_at')
            ->limit(20)
            ->get();

        return view('customer-feedback::analytics.nps', $this->data);
    }

    /**
     * CSAT analytics (service / cleaner / punctuality star ratings)
     */
    public function csat()
    {
        $startDate = request()->start_date ? Carbon::parse(request()->start_date) : now()->subDays(90);
        $endDate   = request()->end_date   ? Carbon::parse(request()->end_date)   : now();

        $completed = NpsSurvey::whereNotNull('completed_at')
            ->whereBetween('completed_at', [$startDate, $endDate])
            ->get();

        $this->avgServiceRating     = $completed->whereNotNull('service_rating')->avg('service_rating');
        $this->avgCleanerRating     = $completed->whereNotNull('cleaner_rating')->avg('cleaner_rating');
        $this->avgPunctualityRating = $completed->whereNotNull('punctuality_rating')->avg('punctuality_rating');
        $this->totalResponses       = $completed->count();
        $this->satisfiedCount       = $completed->where('service_rating', '>=', 4)->count();
        $this->satisfactionRate     = $this->totalResponses > 0
            ? round(($this->satisfiedCount / $this->totalResponses) * 100, 1)
            : 0;

        return view('customer-feedback::analytics.csat', $this->data);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────────────────

    private function getAverageResolutionTime($startDate, $endDate): float
    {
        $resolved = FeedbackTicket::whereNotNull('resolved_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        if ($resolved->isEmpty()) {
            return 0.0;
        }

        $totalHours = $resolved->sum(
            fn ($ticket) => $ticket->resolved_at->diffInHours($ticket->created_at)
        );

        return round($totalHours / $resolved->count(), 2);
    }

    private function getAverageNpsScore($startDate, $endDate): ?float
    {
        $avg = NpsSurvey::whereNotNull('nps_score')
            ->whereNotNull('completed_at')
            ->whereBetween('completed_at', [$startDate, $endDate])
            ->avg('nps_score');

        return $avg !== null ? round((float) $avg, 1) : null;
    }

    private function getTicketsOverTime($startDate, $endDate)
    {
        return FeedbackTicket::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->get()
            ->map(fn ($item) => ['date' => $item->date, 'count' => $item->count]);
    }

    private function getStatusBreakdown()
    {
        return FeedbackTicket::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->map(fn ($item) => ['status' => $item->status, 'count' => $item->count]);
    }

    private function getPriorityBreakdown()
    {
        return FeedbackTicket::selectRaw('priority, COUNT(*) as count')
            ->groupBy('priority')
            ->get()
            ->map(fn ($item) => ['priority' => $item->priority, 'count' => $item->count]);
    }

    private function getFeedbackTypeBreakdown()
    {
        return FeedbackTicket::selectRaw('feedback_type, COUNT(*) as count')
            ->groupBy('feedback_type')
            ->get()
            ->map(fn ($item) => ['type' => $item->feedback_type, 'count' => $item->count]);
    }

    private function getTopAgents(int $limit = 5)
    {
        return FeedbackTicket::whereNotNull('agent_id')
            ->selectRaw('agent_id, COUNT(*) as handled, SUM(CASE WHEN status IN ("resolved","closed") THEN 1 ELSE 0 END) as resolved')
            ->groupBy('agent_id')
            ->orderByDesc('resolved')
            ->limit($limit)
            ->with('agent')
            ->get();
    }

    private function getNpsData($startDate, $endDate): array
    {
        $total = NpsSurvey::whereNotNull('nps_score')
            ->whereNotNull('completed_at')
            ->whereBetween('completed_at', [$startDate, $endDate])
            ->count();

        if ($total === 0) {
            return ['promoters' => 0, 'passives' => 0, 'detractors' => 0];
        }

        $promoters  = NpsSurvey::whereNotNull('completed_at')->where('nps_score', '>=', 9)->whereBetween('completed_at', [$startDate, $endDate])->count();
        $passives   = NpsSurvey::whereNotNull('completed_at')->whereBetween('nps_score', [7, 8])->whereBetween('completed_at', [$startDate, $endDate])->count();
        $detractors = NpsSurvey::whereNotNull('completed_at')->where('nps_score', '<=', 6)->whereNotNull('nps_score')->whereBetween('completed_at', [$startDate, $endDate])->count();

        return [
            'promoters'  => round(($promoters  / $total) * 100, 1),
            'passives'   => round(($passives   / $total) * 100, 1),
            'detractors' => round(($detractors / $total) * 100, 1),
        ];
    }

    private function getNpsScoresOverTime($startDate, $endDate)
    {
        return NpsSurvey::whereNotNull('nps_score')
            ->whereNotNull('completed_at')
            ->whereBetween('completed_at', [$startDate, $endDate])
            ->selectRaw('DATE(completed_at) as date, AVG(nps_score) as avg_score, COUNT(*) as count')
            ->groupBy('date')
            ->get();
    }

    private function getNpsScoreDistribution()
    {
        return NpsSurvey::whereNotNull('nps_score')
            ->selectRaw('nps_score as score, COUNT(*) as count')
            ->groupBy('nps_score')
            ->orderBy('nps_score')
            ->get()
            ->map(fn ($item) => ['score' => $item->score, 'count' => $item->count]);
    }
}
