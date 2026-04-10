<?php

namespace Modules\BookingModule\Http\Controllers\Api\V1\Public;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\BookingModule\Entities\Booking;
use Modules\BookingModule\Entities\BookingPage;
use Modules\BookingModule\Entities\BookingPageRequest;

class BookingWidgetController extends Controller
{
    public function services(Request $request): JsonResponse
    {
        $page = $this->resolvePublishedPage((string) $request->query('slug', ''));

        $services = collect(data_get($page?->settings, 'services', []))
            ->filter()
            ->values()
            ->map(fn ($service, $index) => [
                'id' => $index + 1,
                'name' => (string) $service,
                'slug' => str((string) $service)->slug()->toString(),
            ])
            ->values();

        return response()->json([
            'data' => $services,
            'page' => $page ? ['id' => $page->id, 'slug' => $page->slug, 'title' => $page->title] : null,
        ]);
    }

    public function availability(Request $request): JsonResponse
    {
        $days = max(1, min((int) $request->query('days', 7), 21));
        $windows = ['Morning', 'Midday', 'Afternoon'];
        $slots = [];

        $cursor = Carbon::today();
        while (count($slots) < $days) {
            if (!$cursor->isWeekend()) {
                $slots[] = [
                    'date' => $cursor->toDateString(),
                    'label' => $cursor->format('D j M'),
                    'windows' => $windows,
                ];
            }
            $cursor->addDay();
        }

        return response()->json(['data' => $slots]);
    }

    public function request(Request $request): JsonResponse
    {
        $page = $this->resolvePublishedPage((string) $request->input('slug', ''));

        if (!$page) {
            return response()->json(['message' => 'Published booking page not found.'], 404);
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:191',
            'email' => 'nullable|email|max:191',
            'phone' => 'required|string|max:40',
            'service_name' => 'nullable|string|max:191',
            'postcode' => 'nullable|string|max:20',
            'preferred_date' => 'nullable|date',
            'preferred_window' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:5000',
            'request_type' => 'nullable|string|max:40',
        ]);

        $entry = BookingPageRequest::create(array_merge($validated, [
            'company_id' => $page->company_id,
            'created_by' => Auth::id(),
            'booking_page_id' => $page->id,
            'page_slug' => $page->slug,
            'status' => 'new',
            'payload' => [
                'request_type' => $validated['request_type'] ?? 'booking_request',
                'user_agent' => (string) $request->userAgent(),
                'source' => 'booking_widget_api',
                'source_url' => (string) $request->headers->get('referer', ''),
                'submit_url' => (string) $request->fullUrl(),
            ],
        ]));

        return response()->json([
            'message' => 'Booking request sent successfully.',
            'data' => [
                'id' => $entry->id,
                'status' => $entry->status,
            ],
        ], 201);
    }

    public function quoteRequest(Request $request): JsonResponse
    {
        $request->merge(['request_type' => 'quote_request']);

        return $this->request($request);
    }

    public function portalSummary(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Authentication required.'], 401);
        }

        $user = Auth::user();
        $bookings = Booking::query()
            ->where('customer_id', $user->id)
            ->latest('created_at')
            ->limit(5)
            ->get(['id', 'readable_id', 'booking_status', 'service_schedule', 'created_at']);

        return response()->json([
            'data' => [
                'count' => $bookings->count(),
                'bookings' => $bookings,
            ],
        ]);
    }

    public function portalBookings(): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Authentication required.'], 401);
        }

        $bookings = Booking::query()
            ->where('customer_id', Auth::id())
            ->latest('created_at')
            ->paginate(10, ['id', 'readable_id', 'booking_status', 'service_schedule', 'created_at']);

        return response()->json($bookings);
    }

    public function jobStatus(string $reference): JsonResponse
    {
        $booking = Booking::query()
            ->where('id', $reference)
            ->orWhere('readable_id', is_numeric($reference) ? (int) $reference : -1)
            ->first();

        if (!$booking) {
            return response()->json(['message' => 'Booking not found.'], 404);
        }

        return response()->json([
            'data' => [
                'id' => $booking->id,
                'reference' => $booking->readable_id,
                'status' => $booking->booking_status,
                'scheduled_for' => $booking->service_schedule,
                'is_paid' => (bool) $booking->is_paid,
                'is_verified' => (bool) $booking->is_verified,
            ],
        ]);
    }

    protected function resolvePublishedPage(string $slug): ?BookingPage
    {
        return BookingPage::query()
            ->when($slug !== '', fn ($query) => $query->where('slug', $slug))
            ->where('status', 'published')
            ->latest('id')
            ->first();
    }
}
