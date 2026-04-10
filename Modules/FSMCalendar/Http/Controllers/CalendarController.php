<?php

namespace Modules\FSMCalendar\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMCore\Models\FSMStage;
use Modules\FSMCore\Models\FSMTeam;

class CalendarController extends Controller
{
    /**
     * Main calendar view.
     */
    public function index(Request $request)
    {
        $stages    = FSMStage::orderBy('sequence')->get();
        $teams     = FSMTeam::orderBy('name')->get();
        $workers   = \App\Models\User::orderBy('name')->get();

        return view('fsmcalendar::calendar.index', compact('stages', 'teams', 'workers'));
    }

    /**
     * JSON feed for FullCalendar – returns FSM Orders as events.
     * Supports filters: worker_id, team_id, stage_id.
     */
    public function events(Request $request): JsonResponse
    {
        $start  = $request->query('start');
        $end    = $request->query('end');

        $query = FSMOrder::with(['stage', 'location', 'person', 'team'])
            ->whereNotNull('scheduled_date_start');

        if ($start) {
            $query->where('scheduled_date_start', '>=', $start);
        }
        if ($end) {
            $query->where('scheduled_date_start', '<=', $end);
        }

        // Optional filters
        if ($workerId = $request->query('worker_id')) {
            $query->where('person_id', $workerId);
        }
        if ($teamId = $request->query('team_id')) {
            $query->where('team_id', $teamId);
        }
        if ($stageId = $request->query('stage_id')) {
            $query->where('stage_id', $stageId);
        }

        $orders = $query->get();

        $events = $orders->map(function (FSMOrder $order) {
            $color = $this->resolveColor($order);

            $title = $order->name;
            if ($order->location) {
                $title .= ' – ' . $order->location->name;
            }

            $event = [
                'id'                => $order->id,
                'title'             => $title,
                'start'             => $order->scheduled_date_start?->toIso8601String(),
                'end'               => $order->scheduled_date_end?->toIso8601String(),
                'color'             => $color,
                'textColor'         => '#ffffff',
                'url'               => route('fsmcore.orders.show', $order->id),
                'extendedProps'     => [
                    'orderId'   => $order->id,
                    'stage'     => $order->stage?->name,
                    'worker'    => $order->person?->name,
                    'team'      => $order->team?->name,
                    'location'  => $order->location?->name,
                    'priority'  => $order->priority,
                    'urgent'    => $order->isUrgent(),
                ],
            ];

            // Resource view: assign to worker column when person_id set
            if ($order->person_id) {
                $event['resourceId'] = $order->person_id;
            }

            return $event;
        });

        return response()->json($events->values());
    }

    /**
     * Reschedule an order via drag-and-drop or resize.
     */
    public function reschedule(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'start' => 'required|date',
            'end'   => 'nullable|date|after_or_equal:start',
        ]);

        $order = FSMOrder::findOrFail($id);
        $order->scheduled_date_start = $validated['start'];

        if (!empty($validated['end'])) {
            $order->scheduled_date_end = $validated['end'];
        } else {
            // Preserve original duration when only start changes (drag without end)
            if ($order->scheduled_date_start && $order->scheduled_date_end) {
                $originalDuration = $order->getOriginal('scheduled_date_end')
                    ? \Carbon\Carbon::parse($order->getOriginal('scheduled_date_end'))
                        ->diffInSeconds(\Carbon\Carbon::parse($order->getOriginal('scheduled_date_start')))
                    : 3600;
                $order->scheduled_date_end = \Carbon\Carbon::parse($validated['start'])
                    ->addSeconds($originalDuration);
            }
        }

        $order->save();

        return response()->json(['success' => true, 'order_id' => $order->id]);
    }

    /**
     * Quick-create a new FSM Order from a calendar slot click.
     */
    public function quickCreate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'start'     => 'required|date',
            'end'       => 'nullable|date|after_or_equal:start',
            'worker_id' => 'nullable|integer|exists:users,id',
        ]);

        $prefix = config('fsmcore.order_reference_prefix', 'ORD');
        $name   = $prefix . '-' . \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(8));

        $order = FSMOrder::create([
            'name'                 => $name,
            'scheduled_date_start' => $validated['start'],
            'scheduled_date_end'   => $validated['end'] ?? null,
            'person_id'            => $validated['worker_id'] ?? null,
            'priority'             => '0',
        ]);

        return response()->json([
            'success'  => true,
            'order_id' => $order->id,
            'edit_url' => route('fsmcore.orders.edit', $order->id),
        ], 201);
    }

    /**
     * Return workers as FullCalendar resources for the resource timeline view.
     */
    public function resources(): JsonResponse
    {
        $workers = \App\Models\User::orderBy('name')
            ->get(['id', 'name'])
            ->map(fn ($u) => ['id' => $u->id, 'title' => $u->name]);

        return response()->json($workers->values());
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function resolveColor(FSMOrder $order): string
    {
        if ($order->stage && !empty($order->stage->color)) {
            $raw = $order->stage->color;
            // FSMCore stores color as an integer (Odoo-style index) or hex string
            if (is_numeric($raw)) {
                return $this->odooColorToHex((int) $raw);
            }
            // Already a hex string
            if (str_starts_with((string) $raw, '#')) {
                return $raw;
            }
        }

        return config('fsmcalendar.default_event_color', '#3788d8');
    }

    private function odooColorToHex(int $index): string
    {
        $palette = [
            '#E74C3C', // 0  red
            '#E67E22', // 1  orange
            '#F1C40F', // 2  yellow
            '#2ECC71', // 3  green
            '#1ABC9C', // 4  teal
            '#3498DB', // 5  blue
            '#9B59B6', // 6  purple
            '#E91E63', // 7  pink
            '#795548', // 8  brown
            '#607D8B', // 9  blue-grey
            '#00BCD4', // 10 cyan
            '#8BC34A', // 11 light-green
        ];

        return $palette[$index % count($palette)];
    }
}
