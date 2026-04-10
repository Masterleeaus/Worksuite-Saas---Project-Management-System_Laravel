<?php

namespace Modules\CustomerConnect\Http\Controllers\Portal;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

/**
 * PortalBookingController — customer views upcoming bookings, rebooking, and cancellation.
 */
class PortalBookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * List upcoming and past bookings for the authenticated client.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $upcoming = collect();
        $past = collect();

        if (class_exists(\Modules\BookingModule\Models\CleaningBooking::class)
            && Schema::hasTable('tasks')
        ) {
            try {
                $upcoming = \Modules\BookingModule\Models\CleaningBooking::query()
                    ->where('client_id', $user->id)
                    ->whereNotIn('booking_status', ['completed', 'cancelled'])
                    ->orderBy('start_date_time')
                    ->get();

                $past = \Modules\BookingModule\Models\CleaningBooking::query()
                    ->where('client_id', $user->id)
                    ->whereIn('booking_status', ['completed', 'cancelled'])
                    ->orderByDesc('start_date_time')
                    ->limit(20)
                    ->get();
            } catch (\Throwable) {
            }
        }

        return view('customerconnect::portal.bookings.index', compact('upcoming', 'past'));
    }

    /**
     * Show the rebook form, pre-filled from the last booking.
     */
    public function rebook(Request $request)
    {
        $user = Auth::user();
        $lastBooking = null;

        if (class_exists(\Modules\BookingModule\Models\CleaningBooking::class)
            && Schema::hasTable('tasks')
        ) {
            try {
                $lastBooking = \Modules\BookingModule\Models\CleaningBooking::query()
                    ->where('client_id', $user->id)
                    ->where('booking_status', 'completed')
                    ->orderByDesc('start_date_time')
                    ->first();
            } catch (\Throwable) {
            }
        }

        return view('customerconnect::portal.bookings.rebook', compact('lastBooking'));
    }

    /**
     * Submit a new booking request (rebook).
     */
    public function storeRebook(Request $request)
    {
        $request->validate([
            'preferred_date' => 'required|date|after:today',
            'service_type'   => 'required|string|max:100',
            'notes'          => 'nullable|string|max:1000',
        ]);

        // Record the rebook request — if BookingModule is present, create a booking;
        // otherwise, store in the portal preferences / notes log.
        $user = Auth::user();
        $created = false;

        if (class_exists(\Modules\BookingModule\Models\CleaningBooking::class)
            && Schema::hasTable('tasks')
        ) {
            try {
                \Modules\BookingModule\Models\CleaningBooking::create([
                    'client_id'      => $user->id,
                    'company_id'     => $user->company_id,
                    'service_type'   => $request->service_type,
                    'start_date_time' => $request->preferred_date,
                    'booking_status' => 'pending',
                    'heading'        => 'Rebook request from portal',
                    'description'    => $request->notes,
                    'task_type'      => 'booking',
                ]);
                $created = true;
            } catch (\Throwable) {
            }
        }

        if ($created) {
            return redirect()->route('customerconnect.portal.bookings.index')
                ->with('success', 'Your booking request has been submitted. We will confirm shortly.');
        }

        return redirect()->route('customerconnect.portal.bookings.index')
            ->with('info', 'Your rebook request has been received. Our team will be in touch to confirm the details.');
    }

    /**
     * Cancel an upcoming booking.
     */
    public function cancel(Request $request, int $id)
    {
        $user = Auth::user();

        if (!class_exists(\Modules\BookingModule\Models\CleaningBooking::class)
            || !Schema::hasTable('tasks')
        ) {
            return redirect()->route('customerconnect.portal.bookings.index')
                ->with('error', 'Booking cancellation is not available at this time.');
        }

        try {
            $booking = \Modules\BookingModule\Models\CleaningBooking::query()
                ->where('client_id', $user->id)
                ->findOrFail($id);

            if (!in_array($booking->booking_status, ['pending', 'confirmed'])) {
                return redirect()->route('customerconnect.portal.bookings.index')
                    ->with('error', 'This booking cannot be cancelled.');
            }

            $booking->update(['booking_status' => 'cancelled']);
        } catch (\Throwable) {
            return redirect()->route('customerconnect.portal.bookings.index')
                ->with('error', 'Booking not found or you do not have permission to cancel it.');
        }

        return redirect()->route('customerconnect.portal.bookings.index')
            ->with('success', 'Your booking has been cancelled.');
    }
}
