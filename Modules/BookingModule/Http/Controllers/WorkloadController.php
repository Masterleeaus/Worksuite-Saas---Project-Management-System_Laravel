<?php

namespace Modules\BookingModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\BookingModule\Services\AppointmentQueryService;

class WorkloadController extends Controller
{
    public function unassigned(AppointmentQueryService $query)
    {
        if (!\Modules\BookingModule\Support\AppointmentPermission::check(Auth::user(), 'appointments manage')) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        $appointments = $query->unassigned()->with('assignee')->paginate(20);
        return view('bookingmodule::workload.unassigned', compact('appointments'));
    }

    public function mine(AppointmentQueryService $query)
    {
        if (!\Modules\BookingModule\Support\AppointmentPermission::check(Auth::user(), 'appointments manage')) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        $appointments = $query->mine(Auth::id())->with('assignee')->paginate(20);
        return view('bookingmodule::workload.mine', compact('appointments'));
    }
}
