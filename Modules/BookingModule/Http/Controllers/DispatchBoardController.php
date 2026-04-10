<?php

namespace Modules\BookingModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\BookingModule\Entities\Schedule;
use Modules\BookingModule\Http\Requests\Dispatch\DispatchMoveRequest;
use Modules\BookingModule\Services\Dispatch\DispatchBoardQueryService;
use Modules\BookingModule\Services\Dispatch\DispatchMoveService;

class DispatchBoardController extends Controller
{
    public function __construct(
        protected DispatchBoardQueryService $query,
        protected DispatchMoveService $moveService,
    ) {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->authorizeDispatch();

        $date = $request->input('date');
        $workspace = $request->input('workspace');

        $payload = $this->query->buildBoardPayload($date, $workspace);

        return view('bookingmodule::dispatch.index', $payload);
    }

    public function move(DispatchMoveRequest $request)
    {
        $this->authorizeDispatch();

        $result = $this->moveService->move(
            scheduleId: (int)$request->input('schedule_id'),
            toUserId: $request->input('to_user_id') !== null ? (int)$request->input('to_user_id') : null,
            date: (string)$request->input('date'),
            startTime: (string)$request->input('start_time'),
            endTime: (string)$request->input('end_time'),
            note: (string)($request->input('note') ?? '')
        );

        return response()->json($result, $result['ok'] ? 200 : 422);
    }

    protected function authorizeDispatch(): void
    {
        // Worksuite permission helper differs across installs, so we do both checks.
        if (function_exists('permissionCheck')) {
            abort_unless(permissionCheck('appointment dispatch'), 403);
            return;
        }

        $user = Auth::user();
        if (!$user || (method_exists($user, 'can') && !$user->can('appointment dispatch'))) {
            abort(403);
        }
    }
}
