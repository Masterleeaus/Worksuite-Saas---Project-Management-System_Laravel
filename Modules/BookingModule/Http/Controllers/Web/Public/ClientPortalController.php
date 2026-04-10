<?php

namespace Modules\BookingModule\Http\Controllers\Web\Public;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class ClientPortalController extends Controller
{
    public function show(Request $request)
    {
        $customerId = null;
        if (function_exists('user') && user()) {
            $customerId = user()->id;
        }

        $bookings = collect();
        try {
            if ($customerId) {
                $bookings = DB::table('bookings')->where('customer_user_id', $customerId)->latest('id')->limit(20)->get();
            }
        } catch (\Throwable $e) {
            $bookings = collect();
        }

        return view('bookingmodule::public.client_portal', compact('bookings'));
    }
}
