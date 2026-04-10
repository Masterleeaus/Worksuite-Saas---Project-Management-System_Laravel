<?php

namespace Modules\BookingModule\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Modules\BookingModule\Entities\AppointmentCallback;
use Modules\BookingModule\Entities\Schedule;
use Modules\BookingModule\Events\AppointmentStatus;
use Modules\BookingModule\Services\ScheduleAssignmentService;
use Modules\BookingModule\Services\ScheduleCapacityService;

class ScheduleController extends Controller
{
    public function __construct(
        protected ScheduleAssignmentService $assignmentService,
        protected ScheduleCapacityService $capacityService,
    ) {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of schedules (bookings).
     */
    public function index(Request $request): Renderable
    {
        if (!\Modules\BookingModule\Support\AppointmentPermission::check(Auth::user(), 'schedule manage')) {
            abort(403);
        }

        $schedule = Schedule::where('created_by', creatorId())
            ->where('workspace', getActiveWorkSpace())
            ->with(['creator', 'appointment', 'assignee', 'assignments'])
            ->orderByDesc('id')
            ->get();

        $callbacks = AppointmentCallback::where('created_by', creatorId())
            ->where('workspace', getActiveWorkSpace())
            ->latest('id')
            ->get();

        return view('bookingmodule::schedule.index', compact('schedule', 'callbacks'));
    }

    public function create()
    {
        return redirect()->back();
    }

    public function store(Request $request)
    {
        return redirect()->back();
    }

    /**
     * Show schedule.
     */
    public function show($id): Renderable
    {
        if (!\Modules\BookingModule\Support\AppointmentPermission::check(Auth::user(), 'schedule show')) {
            abort(403);
        }

        try {
            $id = Crypt::decrypt($id);
        } catch (\Throwable $th) {
            abort(403);
        }

        $schedule = Schedule::where('created_by', creatorId())
            ->where('workspace', getActiveWorkSpace())
            ->with(['creator', 'appointment', 'assignee', 'assignments'])
            ->findOrFail($id);

        $callbacks = AppointmentCallback::where('created_by', creatorId())
            ->where('workspace', getActiveWorkSpace())
            ->where('appointment_id', $schedule->appointment_id)
            ->get();

        return view('bookingmodule::schedule.show', compact('schedule', 'callbacks'));
    }

    public function edit($id)
    {
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        return redirect()->back();
    }

    public function destroy($id)
    {
        if (!\Modules\BookingModule\Support\AppointmentPermission::check(Auth::user(), 'schedule delete')) {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

        try {
            $id = Crypt::decrypt($id);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

        $schedule = Schedule::where('created_by', creatorId())
            ->where('workspace', getActiveWorkSpace())
            ->find($id);

        if ($schedule) {
            $schedule->delete();
        }

        return redirect()->back()->with('success', __('Deleted.'));
    }

    /**
     * Action page (approve/reject + assignment)
     */
    public function action($id): Renderable
    {
        if (!\Modules\BookingModule\Support\AppointmentPermission::check(Auth::user(), 'schedule action')) {
            abort(403);
        }

        try {
            $id = Crypt::decrypt($id);
        } catch (\Throwable $th) {
            abort(403);
        }

        $schedule = Schedule::where('created_by', creatorId())
            ->where('workspace', getActiveWorkSpace())
            ->with(['appointment', 'assignee'])
            ->findOrFail($id);

        return view('bookingmodule::schedule.action', compact('schedule'));
    }

    /**
     * Change booking status and optionally assign.
     */
    public function changeaction(Request $request)
    {
        if (!\Modules\BookingModule\Support\AppointmentPermission::check(Auth::user(), 'schedule action')) {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

        $request->validate([
            'schedule_id' => ['required'],
            'status' => ['required', 'string', 'max:40'],
            'assigned_to' => ['nullable', 'integer'],
        ]);

        try {
            $scheduleId = Crypt::decrypt($request->schedule_id);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

        $schedule = Schedule::where('created_by', creatorId())
            ->where('workspace', getActiveWorkSpace())
            ->findOrFail($scheduleId);

        // If assigning as part of approval, enforce capacity rules.
        if ($request->filled('assigned_to')) {
            $this->capacityService->assertCanAssign($schedule, (int)$request->assigned_to);
            $this->assignmentService->assign($schedule, (int)$request->assigned_to, 'status_change');
        }

        $schedule->status = $request->status;
        $schedule->save();

        event(new AppointmentStatus($schedule->id, $schedule->status));

        return redirect()->route('appointment.schedules.index')->with('success', __('Updated.'));
    }
}
