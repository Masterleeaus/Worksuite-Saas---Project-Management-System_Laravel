<?php

namespace Modules\ClientPulse\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ClientPulse\Models\JobRating;

class RatingAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Admin overview: aggregate cleaner ratings + individual feedback entries.
     */
    public function index(Request $request)
    {
        // Per-cleaner aggregate scores
        $cleanerStats = JobRating::with('cleaner')
            ->selectRaw('cleaner_id, AVG(stars) as avg_stars, COUNT(*) as total_ratings')
            ->groupBy('cleaner_id')
            ->orderByDesc('avg_stars')
            ->get();

        // Individual ratings (paginated, filterable)
        $q = JobRating::with(['order', 'client', 'cleaner'])
            ->latest('rated_at');

        if ($request->filled('cleaner_id')) {
            $q->where('cleaner_id', $request->integer('cleaner_id'));
        }
        if ($request->filled('stars')) {
            $q->where('stars', $request->integer('stars'));
        }

        $ratings = $q->paginate(30)->withQueryString();

        return view('clientpulse::admin.ratings.index', compact('cleanerStats', 'ratings'));
    }

    /**
     * Show a single rating entry.
     */
    public function show(int $rating)
    {
        $rating = JobRating::with(['order', 'client', 'cleaner'])->findOrFail($rating);

        return view('clientpulse::admin.ratings.show', compact('rating'));
    }
}
