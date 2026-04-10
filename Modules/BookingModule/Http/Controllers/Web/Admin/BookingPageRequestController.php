<?php

namespace Modules\BookingModule\Http\Controllers\Web\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\BookingModule\Entities\BookingPageRequest;

class BookingPageRequestController extends Controller
{
    public function index(Request $request): Renderable
    {
        $requests = BookingPageRequest::query()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest('id')
            ->paginate(config('app.pagination', 15));

        return view('bookingmodule::admin.page-requests.index', [
            'requests' => $requests,
            'statuses' => ['new', 'triaged', 'scheduled', 'closed'],
        ]);
    }

    public function updateStatus(Request $request, BookingPageRequest $bookingPageRequest): RedirectResponse
    {
        $data = $request->validate(['status' => 'required|in:new,triaged,scheduled,closed']);
        $bookingPageRequest->update($data);

        return back()->with('success', 'Booking page request status updated.');
    }
}
