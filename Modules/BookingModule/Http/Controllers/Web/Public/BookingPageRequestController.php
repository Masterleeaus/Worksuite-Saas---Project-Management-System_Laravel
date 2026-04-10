<?php

namespace Modules\BookingModule\Http\Controllers\Web\Public;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\BookingModule\Entities\BookingPage;
use Modules\BookingModule\Entities\BookingPageRequest;

class BookingPageRequestController extends Controller
{
    public function store(Request $request, string $slug): RedirectResponse|JsonResponse
    {
        $page = BookingPage::query()->where('slug', $slug)->where('status', 'published')->firstOrFail();

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

        BookingPageRequest::create(array_merge($validated, [
            'company_id' => $page->company_id,
            'created_by' => Auth::id(),
            'booking_page_id' => $page->id,
            'page_slug' => $page->slug,
            'status' => 'new',
            'payload' => [
                'request_type' => $validated['request_type'] ?? 'booking_request',
                'user_agent' => (string) $request->userAgent(),
                'source' => 'booking_page_form',
                'source_url' => (string) url()->previous(),
                'submit_url' => (string) $request->fullUrl(),
            ],
        ]));

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Booking request sent successfully.']);
        }

        return back()->with('success', 'Booking request sent successfully. Dispatch can now triage it from Booking page requests.');
    }
}
