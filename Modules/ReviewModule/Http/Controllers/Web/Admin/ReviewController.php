<?php

namespace Modules\ReviewModule\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\ReviewModule\Entities\Review;
use Modules\ReviewModule\Entities\ReviewReply;

class ReviewController extends Controller
{
    private Review $review;
    private ReviewReply $reviewReply;

    public function __construct(Review $review, ReviewReply $reviewReply)
    {
        $this->review = $review;
        $this->reviewReply = $reviewReply;
    }

    /**
     * Review management list.
     */
    public function index(Request $request)
    {
        abort_if(!in_array('reviewmodule', user_modules()) && user()->permission('view_reviews') == 'none', 403);

        $query = $this->review->with(['customer', 'service', 'provider', 'reviewReply']);

        if ($request->filled('status')) {
            $query->where('moderation_status', $request->status);
        }

        if ($request->filled('rating')) {
            $query->where('review_rating', $request->rating);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('review_comment', 'like', "%{$search}%")
                  ->orWhere('readable_id', 'like', "%{$search}%");
            });
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total'     => $this->review->count(),
            'pending'   => $this->review->where('moderation_status', 'pending')->count(),
            'approved'  => $this->review->where('moderation_status', 'approved')->count(),
            'published' => $this->review->where('moderation_status', 'published')->count(),
            'rejected'  => $this->review->where('moderation_status', 'rejected')->count(),
            'avg_rating' => round($this->review->avg('review_rating'), 1),
        ];

        return view('reviewmodule::index', compact('reviews', 'stats'));
    }

    /**
     * Review detail with response.
     */
    public function show($id)
    {
        abort_if(user()->permission('view_reviews') == 'none', 403);

        $review = $this->review->with(['customer', 'service', 'provider', 'reviewReply.user'])->findOrFail($id);

        return view('reviewmodule::show', compact('review'));
    }

    /**
     * Approve a review.
     */
    public function approve(Request $request, $id)
    {
        abort_if(user()->permission('moderate_reviews') == 'none', 403);

        $review = $this->review->findOrFail($id);
        $review->moderation_status = 'approved';
        $review->save();

        return response()->json(['status' => 'success', 'message' => __('reviewmodule::modules.review_approved')]);
    }

    /**
     * Reject a review.
     */
    public function reject(Request $request, $id)
    {
        abort_if(user()->permission('moderate_reviews') == 'none', 403);

        $review = $this->review->findOrFail($id);
        $review->moderation_status = 'rejected';
        $review->save();

        return response()->json(['status' => 'success', 'message' => __('reviewmodule::modules.review_rejected')]);
    }

    /**
     * Publish a review (makes it visible publicly).
     */
    public function publish(Request $request, $id)
    {
        abort_if(user()->permission('publish_review') == 'none', 403);

        $review = $this->review->findOrFail($id);
        $review->moderation_status = 'published';
        $review->is_active = 1;
        $review->save();

        return response()->json(['status' => 'success', 'message' => __('reviewmodule::modules.review_published')]);
    }

    /**
     * Business owner response to a review.
     */
    public function respond(Request $request, $id)
    {
        abort_if(user()->permission('respond_to_reviews') == 'none', 403);

        $validator = Validator::make($request->all(), [
            'reply' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $review = $this->review->findOrFail($id);

        $reply = $this->reviewReply->firstOrNew(['review_id' => $review->id]);
        $reply->review_id = $review->id;
        $reply->user_id   = user()->id;
        $reply->reply     = $request->reply;
        $reply->company_id = user()->company_id ?? null;
        $reply->save();

        return response()->json([
            'status'  => 'success',
            'message' => __('reviewmodule::modules.reply_saved'),
            'reply'   => $reply->reply,
        ]);
    }

    /**
     * Review analytics summary.
     */
    public function analytics(Request $request)
    {
        abort_if(user()->permission('view_reviews') == 'none', 403);

        $ratingBreakdown = DB::table('reviews')
            ->select('review_rating', DB::raw('count(*) as total'))
            ->groupBy('review_rating')
            ->orderBy('review_rating', 'desc')
            ->get();

        $categoryBreakdown = DB::table('reviews')
            ->select(
                DB::raw('AVG(rating_punctuality) as avg_punctuality'),
                DB::raw('AVG(rating_quality) as avg_quality'),
                DB::raw('AVG(rating_value) as avg_value'),
                DB::raw('AVG(rating_communication) as avg_communication'),
                DB::raw('AVG(review_rating) as avg_overall')
            )
            ->first();

        $monthlyTrend = DB::table('reviews')
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('count(*) as total'), DB::raw('AVG(review_rating) as avg_rating'))
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        return view('reviewmodule::analytics', compact('ratingBreakdown', 'categoryBreakdown', 'monthlyTrend'));
    }

    /**
     * Public tokenised review submission form.
     */
    public function publicForm($token)
    {
        $review = $this->review->where('review_token', $token)
            ->whereNull('review_comment')
            ->firstOrFail();

        return view('reviewmodule::public.form', compact('review', 'token'));
    }

    /**
     * Store public review submission.
     */
    public function publicStore(Request $request, $token)
    {
        $review = $this->review->where('review_token', $token)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'review_rating'       => 'required|integer|min:1|max:5',
            'review_comment'      => 'nullable|string|max:2000',
            'rating_punctuality'  => 'nullable|integer|min:1|max:5',
            'rating_quality'      => 'nullable|integer|min:1|max:5',
            'rating_value'        => 'nullable|integer|min:1|max:5',
            'rating_communication'=> 'nullable|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $review->review_rating        = $request->review_rating;
        $review->review_comment       = $request->review_comment;
        $review->rating_punctuality   = $request->rating_punctuality;
        $review->rating_quality       = $request->rating_quality;
        $review->rating_value         = $request->rating_value;
        $review->rating_communication = $request->rating_communication;
        $review->moderation_status    = 'pending';
        $review->submitted_at         = now();
        $review->save();

        return view('reviewmodule::public.thankyou', compact('review'));
    }
}
