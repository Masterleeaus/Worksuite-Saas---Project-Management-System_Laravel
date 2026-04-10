<?php

namespace Modules\BookingModule\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\BookingModule\Entities\Schedule;
use Modules\BookingModule\Http\Requests\BulkAssignSchedulesRequest;
use Modules\BookingModule\Http\Requests\UpdateStaffCapacityRequest;
use Modules\BookingModule\Entities\AppointmentStaffCapacity;
use Modules\BookingModule\Services\BulkScheduleAssignmentService;
use Modules\BookingModule\Services\ScheduleCapacityService;
use Modules\BookingModule\Services\ScheduleConflictService;

class ScheduleWorkloadController extends Controller
{
    public function __construct(
        protected BulkScheduleAssignmentService $bulkService,
        protected ScheduleCapacityService $capacityService,
        protected ScheduleConflictService $conflictService
    ) {
        $this->middleware(['auth']);
    }

    public function unassigned(Request $request): Renderable
    {
        if (!\Modules\BookingModule\Support\AppointmentPermission::check(Auth::user(), 'schedule manage') && !\Modules\BookingModule\Support\AppointmentPermission::check(Auth::user(), 'schedule show')) {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

        $query = Schedule::query()
            ->where('created_by', creatorId())
            ->where('workspace', getActiveWorkSpace())
            ->where(function($q){
                $q->whereNull('assigned_to')->orWhere('assignment_status', 'unassigned');
            })
            ->with(['appointment', 'assignee', 'assignments']);

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $schedules = $query->orderBy('date', 'desc')->orderBy('start_time', 'asc')->paginate(25);

        $users = User::where('created_by', creatorId())
            ->where('workspace_id', getActiveWorkSpace())
            ->emp()->get()->pluck('name', 'id');

        return view('bookingmodule::schedule.unassigned', compact('schedules', 'users'));
    }

    public function mine(Request $request): Renderable
    {
        if (!\Modules\BookingModule\Support\AppointmentPermission::check(Auth::user(), 'schedule show')) {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

        $userId = Auth::id();

        $query = Schedule::query()
            ->where('created_by', creatorId())
            ->where('workspace', getActiveWorkSpace())
            ->where(function($q) use ($userId){
                $q->where('assigned_to', $userId)->orWhere('user_id', $userId);
            })
            ->with(['appointment', 'assignee', 'assignments']);

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $schedules = $query->orderBy('date', 'desc')->orderBy('start_time', 'asc')->paginate(25);

        return view('bookingmodule::schedule.mine', compact('schedules'));
    }

    public function bulkAssign(BulkAssignSchedulesRequest $request)
    {
        $scheduleIds = array_map('intval', $request->input('schedule_ids', []));
        $assignedTo = $request->filled('assigned_to') ? (int)$request->input('assigned_to') : null;
        $note = $request->input('note');

        $result = $this->bulkService->bulkAssign($scheduleIds, $assignedTo, $note, function($schedule, $userId) {
            if ($userId === null) {
                return [true, null];
            }

            [$ok, $message] = $this->capacityService->canAssignUserToSchedule($schedule, (int)$userId);
            if (!$ok) {
                return [$ok, $message];
            }

            $effective = $this->capacityService->getEffectiveCapacity($schedule->created_by, $schedule->workspace, (int)$userId);
            if ($effective['enforce_conflicts'] && $this->conflictService->hasConflict($schedule, (int)$userId, $effective['count_pending_too'])) {
                return [false, __('bookingmodule::capacity.errors.conflict')];
            }

            return [true, null];
        });

        $msg = __('bookingmodule::capacity.bulk.done', ['assigned' => $result['assigned'], 'skipped' => $result['skipped']]);
        if (!empty($result['errors'])) {
            $msg .= '<br><span class="text-danger">' . implode('<br>', array_slice($result['errors'], 0, 5)) . '</span>';
        }

        return redirect()->back()->with('success', $msg);
    }

    public function staffCapacity(): Renderable
    {
        if (!\Modules\BookingModule\Support\AppointmentPermission::check(Auth::user(), 'schedule manage')) {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

        $users = User::where('created_by', creatorId())
            ->where('workspace_id', getActiveWorkSpace())
            ->emp()->get();

        $capacities = AppointmentStaffCapacity::where('created_by', creatorId())
            ->where('workspace', getActiveWorkSpace())
            ->get()->keyBy('user_id');

        return view('bookingmodule::settings.staff_capacity', compact('users', 'capacities'));
    }

    public function updateStaffCapacity(UpdateStaffCapacityRequest $request)
    {
        $cap = AppointmentStaffCapacity::firstOrNew([
            'created_by' => creatorId(),
            'workspace' => getActiveWorkSpace(),
            'user_id' => (int)$request->user_id,
        ]);

        $cap->max_per_day = $request->filled('max_per_day') ? (int)$request->max_per_day : null;
        $cap->max_per_slot = $request->filled('max_per_slot') ? (int)$request->max_per_slot : null;
        $cap->enforce_conflicts = (bool)$request->input('enforce_conflicts', true);
        $cap->count_pending_too = (bool)$request->input('count_pending_too', false);
        $cap->save();

        return redirect()->back()->with('success', __('bookingmodule::capacity.staff.saved'));
    }
}
