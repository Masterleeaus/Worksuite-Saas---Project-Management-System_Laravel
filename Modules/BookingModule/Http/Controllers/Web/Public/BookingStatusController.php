<?php

namespace Modules\BookingModule\Http\Controllers\Web\Public;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\BookingModule\Entities\Booking;

class BookingStatusController extends Controller
{
    public function show(Request $request): Renderable
    {
        $booking = null;
        $code = trim((string) $request->get('reference'));

        if ($code !== '') {
            $booking = Booking::query()
                ->where('id', $code)
                ->orWhere('readable_id', is_numeric($code) ? (int) $code : -1)
                ->first();
        }

        return view('bookingmodule::public.page-builder.status', [
            'booking' => $booking,
            'reference' => $code,
        ]);
    }
}
