<?php

namespace Modules\FSMAvailability\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMAvailability\Models\FSMAvailabilityRule;
use Modules\FSMAvailability\Models\FSMAvailabilityException;
use App\Models\User;
use Carbon\Carbon;

class AvailabilityCalendarController extends Controller
{
    /**
     * Week calendar view for a single worker showing rules and exceptions.
     */
    public function index(Request $request)
    {
        $workers = User::orderBy('name')->get();

        $userId = $request->integer('person_id') ?: ($workers->first()?->id ?? null);
        $worker = $userId ? User::find($userId) : null;

        $weekStart = $request->filled('week')
            ? Carbon::parse($request->get('week'))->startOfWeek(Carbon::MONDAY)
            : Carbon::today()->startOfWeek(Carbon::MONDAY);

        $weekDays = collect(range(0, 6))->map(fn($i) => $weekStart->copy()->addDays($i));

        $rules = $worker
            ? FSMAvailabilityRule::where('person_id', $worker->id)->where('active', true)->get()->keyBy('day_of_week')
            : collect();

        $exceptions = $worker
            ? FSMAvailabilityException::where('person_id', $worker->id)
                ->where('state', 'approved')
                ->where('date_start', '<', $weekStart->copy()->endOfWeek())
                ->where('date_end', '>', $weekStart)
                ->get()
            : collect();

        $dayMap = [
            1 => 'mon', 2 => 'tue', 3 => 'wed',
            4 => 'thu', 5 => 'fri', 6 => 'sat', 7 => 'sun',
        ];

        return view('fsmavailability::calendar.index', compact(
            'workers', 'worker', 'weekStart', 'weekDays', 'rules', 'exceptions', 'dayMap'
        ));
    }
}
