<?php

namespace Modules\CustomerConnect\Http\Controllers\Portal;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

/**
 * PortalReviewController — customer submits post-job reviews from the self-service portal.
 * Integrates with ReviewModule when available.
 */
class PortalReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the review submission form for a completed booking.
     */
    public function create(Request $request)
    {
        $bookingId = $request->get('booking_id');
        $booking = null;

        if ($bookingId
            && class_exists(\Modules\BookingModule\Models\CleaningBooking::class)
            && Schema::hasTable('tasks')
        ) {
            try {
                $booking = \Modules\BookingModule\Models\CleaningBooking::query()
                    ->where('client_id', Auth::id())
                    ->where('booking_status', 'completed')
                    ->find($bookingId);
            } catch (\Throwable) {
            }
        }

        return view('customerconnect::portal.reviews.create', compact('booking'));
    }

    /**
     * Store a review submitted from the portal.
     */
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'nullable|integer',
            'rating'     => 'required|integer|min:1|max:5',
            'review'     => 'nullable|string|max:2000',
        ]);

        $user = Auth::user();
        $stored = false;

        // Delegate to ReviewModule if present
        if (class_exists(\Modules\ReviewModule\Http\Controllers\ReviewController::class)) {
            try {
                $stored = $this->storeViaReviewModule($request, $user);
            } catch (\Throwable) {
            }
        }

        if (!$stored) {
            // Fallback: log the review in the portal preferences notes
            // so it's not lost even without ReviewModule
            \Illuminate\Support\Facades\Log::info('CustomerConnect portal review submitted', [
                'user_id'    => $user->id,
                'booking_id' => $request->booking_id,
                'rating'     => $request->rating,
                'review'     => $request->review,
            ]);
        }

        return redirect()->route('customerconnect.portal.bookings.index')
            ->with('success', 'Thank you for your review! Your feedback helps us improve our service.');
    }

    /**
     * Attempt to store via ReviewModule facade / direct model.
     */
    private function storeViaReviewModule(Request $request, $user): bool
    {
        // Try to find a Review model in ReviewModule
        foreach ([
            \Modules\ReviewModule\Entities\Review::class,
            \Modules\ReviewModule\Models\Review::class,
        ] as $class) {
            if (class_exists($class) && Schema::hasTable('reviews')) {
                $class::create([
                    'user_id'    => $user->id,
                    'booking_id' => $request->booking_id,
                    'rating'     => $request->rating,
                    'review'     => $request->review,
                    'company_id' => $user->company_id,
                ]);
                return true;
            }
        }

        return false;
    }
}
