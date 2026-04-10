<?php

namespace Modules\FSMAvailability\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMAvailability\Models\FSMAvailabilityRule;
use Modules\FSMAvailability\Models\FSMAvailabilityException;
use App\Models\User;
use Carbon\Carbon;

class AvailabilityGridController extends Controller
{
    /**
     * Admin grid: rows = workers, columns = days of a selected week.
     * Cell status = available / unavailable (approved exception) / partial (outside rule hours).
     */
    public function index(Request $request)
    {
        $weekStart = $request->filled('week')
            ? Carbon::parse($request->get('week'))->startOfWeek(Carbon::MONDAY)
            : Carbon::today()->startOfWeek(Carbon::MONDAY);

        $weekEnd  = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);
        $weekDays = collect(range(0, 6))->map(fn($i) => $weekStart->copy()->addDays($i));

        $dayMap = [
            1 => 'mon', 2 => 'tue', 3 => 'wed',
            4 => 'thu', 5 => 'fri', 6 => 'sat', 7 => 'sun',
        ];

        $workers = User::orderBy('name')->get();

        // Gather all approved exceptions for this week, grouped by person_id.
        $rawExceptions = FSMAvailabilityException::where('state', 'approved')
            ->where('date_start', '<', $weekEnd)
            ->where('date_end', '>', $weekStart)
            ->get();

        $exceptionsByPerson = [];
        foreach ($rawExceptions as $ex) {
            $exceptionsByPerson[$ex->person_id][] = $ex;
        }

        // Gather all active rules grouped by person_id.
        $rawRules = FSMAvailabilityRule::where('active', true)->get();
        $rulesByPerson = [];
        foreach ($rawRules as $rule) {
            $rulesByPerson[$rule->person_id][$rule->day_of_week] = $rule;
        }

        // Build grid: grid[$workerId][$dateString] = 'available' | 'unavailable' | 'no_rule'
        $grid = [];
        foreach ($workers as $worker) {
            $grid[$worker->id] = [];
            foreach ($weekDays as $day) {
                $dow     = $dayMap[$day->isoWeekday()] ?? null;
                $dateStr = $day->toDateString();

                // Check exceptions first.
                $hasException = false;
                foreach ($exceptionsByPerson[$worker->id] ?? [] as $ex) {
                    if ($ex->date_start->toDateString() <= $dateStr
                        && $ex->date_end->toDateString() >= $dateStr) {
                        $hasException = true;
                        break;
                    }
                }

                if ($hasException) {
                    $grid[$worker->id][$dateStr] = 'unavailable';
                    continue;
                }

                $rule = $rulesByPerson[$worker->id][$dow] ?? null;
                $grid[$worker->id][$dateStr] = $rule ? 'available' : 'no_rule';
            }
        }

        return view('fsmavailability::grid.index', compact(
            'workers', 'weekStart', 'weekDays', 'grid'
        ));
    }
}
