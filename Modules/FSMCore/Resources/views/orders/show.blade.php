@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Order: {{ $order->name }}</h2>
    <div class="d-flex gap-2">
        @if(class_exists(\Modules\FSMSkill\Http\Controllers\OrderSkillController::class))
            <a href="{{ route('fsmskill.order-skills.index', $order->id) }}" class="btn btn-outline-info">Skill Requirements</a>
        @endif
        @if($order->person_id && class_exists(\Modules\FSMAvailability\Http\Controllers\AvailabilityCalendarController::class))
            <a href="{{ route('fsmavailability.calendar.index', ['person_id' => $order->person_id]) }}" class="btn btn-outline-warning">🗓 Availability</a>
        @endif
        @if(class_exists(\Modules\FSMTimesheet\Http\Controllers\TimesheetController::class))
            <a href="{{ route('fsmtimesheet.timesheets.index', $order->id) }}" class="btn btn-outline-secondary">⏱ Timesheets</a>
        @endif
        <a href="{{ route('fsmcore.orders.edit', $order->id) }}" class="btn btn-primary">Edit</a>
        <a href="{{ route('fsmcore.orders.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

@if(session('availability_warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        🗓 {{ session('availability_warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('skill_warning'))
    @php $sw = (string) session('skill_warning'); $isError = str_starts_with($sw, 'Skill mismatch'); @endphp
    <div class="alert {{ $isError ? 'alert-danger' : 'alert-warning' }} alert-dismissible fade show" role="alert">
        ⚠ {{ $sw }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-3">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header fw-semibold">Order Details</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Reference</dt><dd class="col-sm-8">{{ $order->name }}</dd>
                    <dt class="col-sm-4">Stage</dt>
                    <dd class="col-sm-8">
                        @if($order->stage)
                            <span class="badge" style="background:{{ $order->stage->color ?? '#6c757d' }};">{{ $order->stage->name }}</span>
                        @else —
                        @endif
                    </dd>
                    <dt class="col-sm-4">Priority</dt>
                    <dd class="col-sm-8">
                        @if($order->priority === '1')
                            <span class="badge bg-danger">Urgent</span>
                        @else
                            <span class="badge bg-secondary">Normal</span>
                        @endif
                    </dd>
                    <dt class="col-sm-4">Location</dt><dd class="col-sm-8">{{ $order->location?->name ?? '—' }}</dd>
                    <dt class="col-sm-4">Team</dt><dd class="col-sm-8">{{ $order->team?->name ?? '—' }}</dd>
                    <dt class="col-sm-4">Worker</dt><dd class="col-sm-8">{{ $order->person?->name ?? '—' }}</dd>
                    <dt class="col-sm-4">Vehicle / Van</dt>
                    <dd class="col-sm-8">
                        @if($order->vehicle_id && class_exists(\Modules\FSMVehicle\Models\FSMVehicle::class))
                            @php $vehicle = $order->vehicle; @endphp
                            @if($vehicle)
                                <a href="{{ route('fsmvehicle.vehicles.show', $vehicle->id) }}">{{ $vehicle->name }}</a>
                                @if($vehicle->license_plate)
                                    <span class="text-muted">({{ $vehicle->license_plate }})</span>
                                @endif
                            @else
                                —
                            @endif
                        @else
                            —
                        @endif
                    </dd>
                    <dt class="col-sm-4">Template</dt><dd class="col-sm-8">{{ $order->template?->name ?? '—' }}</dd>
                    <dt class="col-sm-4">Scheduled Start</dt><dd class="col-sm-8">{{ $order->scheduled_date_start?->format('d M Y H:i') ?? '—' }}</dd>
                    <dt class="col-sm-4">Scheduled End</dt><dd class="col-sm-8">{{ $order->scheduled_date_end?->format('d M Y H:i') ?? '—' }}</dd>
                    <dt class="col-sm-4">Actual Start</dt><dd class="col-sm-8">{{ $order->date_start?->format('d M Y H:i') ?? '—' }}</dd>
                    <dt class="col-sm-4">Actual End</dt><dd class="col-sm-8">{{ $order->date_end?->format('d M Y H:i') ?? '—' }}</dd>
                    <dt class="col-sm-4">Description</dt><dd class="col-sm-8">{{ $order->description ?? '—' }}</dd>
                </dl>
            </div>
        </div>

        @if($order->equipment->isNotEmpty())
        <div class="card mb-3">
            <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
                <span>Equipment</span>
            </div>
            <div class="card-body">
                @foreach($order->equipment as $eq)
                    <div class="d-inline-flex align-items-center gap-2 me-2 mb-1">
                        <span class="badge bg-info text-dark">{{ $eq->name }}</span>
                        @if(class_exists(\Modules\FSMEquipment\Http\Controllers\RepairOrderController::class))
                            <a href="{{ route('fsmequipment.repair-orders.create', ['equipment_id' => $eq->id, 'fsm_order_id' => $order->id]) }}"
                               class="btn btn-sm btn-outline-danger py-0">🔧 Report Fault</a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($order->tags->isNotEmpty())
        <div class="card mb-3">
            <div class="card-header fw-semibold">Tags</div>
            <div class="card-body">
                @foreach($order->tags as $tag)
                    <span class="badge me-1" style="background:{{ $tag->color ?? '#6c757d' }};">{{ $tag->name }}</span>
                @endforeach
            </div>
        </div>
        @endif

        @if(class_exists(\Modules\FSMTimesheet\Models\FSMTimesheetLine::class) && \Illuminate\Support\Facades\Schema::hasTable('fsm_timesheet_lines'))
        @php
            $tsLines        = \Modules\FSMTimesheet\Models\FSMTimesheetLine::where('fsm_order_id', $order->id)->get();
            $effectiveHours = (float) $tsLines->sum('unit_amount');
            $plannedHours   = ($order->scheduled_date_start && $order->scheduled_date_end)
                ? round($order->scheduled_date_start->diffInMinutes($order->scheduled_date_end) / 60, 2)
                : 0.0;
            $tsProgress     = $plannedHours > 0 ? min(100, round(($effectiveHours / $plannedHours) * 100)) : 0;
            $tsProgressClass = $tsProgress >= 100 ? 'bg-danger' : ($tsProgress >= 75 ? 'bg-warning' : 'bg-success');
        @endphp
        <div class="card mb-3">
            <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
                <span>Timesheets</span>
                <a href="{{ route('fsmtimesheet.timesheets.index', $order->id) }}" class="btn btn-sm btn-outline-primary">Manage</a>
            </div>
            <div class="card-body">
                <div class="row text-center mb-2">
                    <div class="col">
                        <div class="fw-bold">{{ number_format($plannedHours, 2) }} h</div>
                        <div class="text-muted small">Planned</div>
                    </div>
                    <div class="col">
                        <div class="fw-bold text-primary">{{ number_format($effectiveHours, 2) }} h</div>
                        <div class="text-muted small">Logged</div>
                    </div>
                    <div class="col">
                        <div class="fw-bold {{ ($plannedHours - $effectiveHours) <= 0 ? 'text-danger' : 'text-success' }}">
                            {{ number_format(max(0, $plannedHours - $effectiveHours), 2) }} h
                        </div>
                        <div class="text-muted small">Remaining</div>
                    </div>
                </div>
                @if($plannedHours > 0)
                <div class="progress" style="height: 14px;">
                    <div class="progress-bar {{ $tsProgressClass }}" role="progressbar"
                         style="width: {{ $tsProgress }}%;">{{ $tsProgress }}%</div>
                </div>
                @endif
                <div class="mt-2 text-muted small">{{ $tsLines->count() }} line(s) logged</div>
            </div>
        </div>
        @endif

        @if(class_exists(\Modules\FSMSkill\Models\FSMOrderSkillRequirement::class) && \Illuminate\Support\Facades\Schema::hasTable('fsm_order_skill_requirements'))
        @php
            $skillReqs = \Modules\FSMSkill\Models\FSMOrderSkillRequirement::with(['skill.skillType', 'skillLevel'])
                ->where('fsm_order_id', $order->id)->get();
        @endphp
        <div class="card mb-3">
            <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
                <span>Skill Requirements</span>
                <a href="{{ route('fsmskill.order-skills.index', $order->id) }}" class="btn btn-sm btn-outline-info">Manage</a>
            </div>
            @if($skillReqs->isEmpty())
                <div class="card-body text-muted">No skill requirements set.</div>
            @else
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-light"><tr><th>Skill</th><th>Type</th><th>Min. Level</th>
                    @if($order->person_id)<th>Match</th>@endif
                    </tr></thead>
                    <tbody>
                    @foreach($skillReqs as $req)
                        @php
                            $matchClass = '';
                            $matchIcon  = '';
                            if ($order->person_id && class_exists(\Modules\FSMSkill\Services\SkillMatchService::class)) {
                                $empSkill = \Modules\FSMSkill\Models\FSMEmployeeSkill::where('user_id', $order->person_id)
                                    ->where('skill_id', $req->skill_id)->first();
                                if (!$empSkill) {
                                    $matchClass = 'table-danger'; $matchIcon = '✘';
                                } elseif ($empSkill->isExpired()) {
                                    $matchClass = 'table-danger'; $matchIcon = '✘ expired';
                                } elseif ($empSkill->isExpiringSoon()) {
                                    $matchClass = 'table-warning'; $matchIcon = '⚠ soon';
                                } else {
                                    $matchClass = 'table-success'; $matchIcon = '✔';
                                }
                            }
                        @endphp
                        <tr class="{{ $matchClass }}">
                            <td>{{ $req->skill?->name ?? '—' }}</td>
                            <td>{{ $req->skill?->skillType?->name ?? '—' }}</td>
                            <td>{{ $req->skillLevel?->name ?? 'Any' }}</td>
                            @if($order->person_id)<td>{{ $matchIcon }}</td>@endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
        @endif
    </div>

    <div class="col-md-4">
        {{-- FSMAvailability: show worker availability status when module is installed --}}
        @if($order->person_id && class_exists(\Modules\FSMAvailability\Services\AvailabilityService::class) && \Illuminate\Support\Facades\Schema::hasTable('fsm_availability_exceptions'))
        @php
            $availResult = app(\Modules\FSMAvailability\Services\AvailabilityService::class)->checkAvailability(
                $order->person_id,
                $order->scheduled_date_start ? \Carbon\Carbon::parse($order->scheduled_date_start) : now(),
                $order->scheduled_date_end   ? \Carbon\Carbon::parse($order->scheduled_date_end)   : now()->addHour()
            );
        @endphp
        <div class="card mb-3">
            <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
                <span>🗓 Worker Availability</span>
                <a href="{{ route('fsmavailability.calendar.index', ['person_id' => $order->person_id]) }}"
                   class="btn btn-sm btn-outline-info">View Calendar</a>
            </div>
            <div class="card-body">
                @if($availResult['available'])
                    <div class="text-success fw-semibold">✔ Worker is available for this job window.</div>
                @else
                    <div class="text-danger fw-semibold">✘ Worker is unavailable</div>
                    @if($availResult['reason'])
                        <div class="text-muted small mt-1">{{ $availResult['reason'] }}</div>
                    @endif
                @endif
            </div>
        </div>
        @endif

        @if($order->location)
        <div class="card">
            <div class="card-header fw-semibold">Location</div>
            <div class="card-body">
                <strong>{{ $order->location->name }}</strong><br>
                @if($order->location->street){{ $order->location->street }}<br>@endif
                @if($order->location->city){{ $order->location->city }}, @endif
                @if($order->location->state){{ $order->location->state }} @endif
                @if($order->location->zip){{ $order->location->zip }}@endif
                @if($order->location->country)<br>{{ $order->location->country }}@endif
                @if($order->location->latitude && $order->location->longitude)
                <div class="mt-2 small text-muted">
                    GPS: {{ $order->location->latitude }}, {{ $order->location->longitude }}
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- FSMRecurring: show recurring schedule panel when module is installed --}}
        @if(class_exists(\Modules\FSMRecurring\Models\FSMRecurring::class) && \Illuminate\Support\Facades\Schema::hasTable('fsm_recurrings'))
        @php
            $orderRecurringId = $order->fsm_recurring_id ?? null;
            $recurring = $orderRecurringId
                ? \Modules\FSMRecurring\Models\FSMRecurring::with(['frequencySet', 'location'])->find($orderRecurringId)
                : null;
        @endphp
        @if($recurring)
        <div class="card mt-3">
            <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
                <span>♻ Recurring Schedule</span>
                <a href="{{ route('fsmrecurring.recurring.show', $recurring->id) }}" class="btn btn-sm btn-outline-info">View Chain</a>
            </div>
            <div class="card-body">
                @php
                    $stateColors = ['draft' => 'secondary', 'progress' => 'success', 'suspend' => 'warning', 'close' => 'dark'];
                @endphp
                <dl class="row mb-0 small">
                    <dt class="col-5">Schedule</dt>
                    <dd class="col-7"><a href="{{ route('fsmrecurring.recurring.show', $recurring->id) }}">{{ $recurring->name }}</a></dd>
                    <dt class="col-5">State</dt>
                    <dd class="col-7">
                        <span class="badge bg-{{ $stateColors[$recurring->state] ?? 'secondary' }}">
                            {{ \Modules\FSMRecurring\Models\FSMRecurring::$states[$recurring->state] ?? $recurring->state }}
                        </span>
                    </dd>
                    <dt class="col-5">Frequency</dt>
                    <dd class="col-7">{{ $recurring->frequencySet?->name ?? '—' }}</dd>
                    <dt class="col-5">Orders</dt>
                    <dd class="col-7"><span class="badge bg-info text-dark">{{ $recurring->orders()->count() }}</span></dd>
                </dl>
            </div>
        </div>
        @endif
        @endif
    </div>
</div>
@endsection
