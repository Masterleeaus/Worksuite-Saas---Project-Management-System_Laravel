<?php

namespace Modules\BookingModule\Http\Controllers\Web\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Modules\BookingModule\Entities\Booking;
use Modules\BookingModule\Entities\BookingPageRequest;

class DispatchBoardController extends Controller
{
    public function index(): Renderable
    {
        $columns = [
            'pending' => 'Pending',
            'accepted' => 'Accepted',
            'ongoing' => 'Ongoing',
            'completed' => 'Completed',
            'canceled' => 'Canceled',
        ];

        $bookingsByStatus = [];
        foreach (array_keys($columns) as $status) {
            $bookingsByStatus[$status] = Booking::query()
                ->where('booking_status', $status)
                ->latest('created_at')
                ->limit(15)
                ->get();
        }

        $pageRequests = BookingPageRequest::query()->latest('id')->limit(12)->get();

        return view('bookingmodule::admin.dispatch.board', compact('columns', 'bookingsByStatus', 'pageRequests'));
    }
}
