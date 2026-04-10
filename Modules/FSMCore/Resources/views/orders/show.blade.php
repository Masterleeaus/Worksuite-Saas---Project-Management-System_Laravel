@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Order: {{ $order->name }}</h2>
    <div class="d-flex gap-2">
        @if(class_exists(\Modules\FSMSkill\Http\Controllers\OrderSkillController::class))
            <a href="{{ route('fsmskill.order-skills.index', $order->id) }}" class="btn btn-outline-info">Skill Requirements</a>
        @endif
        <a href="{{ route('fsmcore.orders.edit', $order->id) }}" class="btn btn-primary">Edit</a>
        <a href="{{ route('fsmcore.orders.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

@if(session('skill_warning'))
    @php $sw = session('skill_warning'); $isError = str_starts_with($sw, 'Skill mismatch'); @endphp
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
            <div class="card-header fw-semibold">Equipment</div>
            <div class="card-body">
                @foreach($order->equipment as $eq)
                    <span class="badge bg-info text-dark me-1">{{ $eq->name }}</span>
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

        @if(class_exists(\Modules\FSMSkill\Models\FSMOrderSkillRequirement::class) && \Illuminate\Support\Facades\Schema::hasTable('fsm_order_skill_requirements'))
        @php
            $skillReqs = \Modules\FSMSkill\Models\FSMOrderSkillRequirement::with(['skill.skillType', 'skillLevel'])
                ->where('fsm_order_id', $order->id)->get();
        @endphp
        @if($skillReqs->isNotEmpty() || true)
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
        @endif
    </div>

    <div class="col-md-4">
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
    </div>
</div>
@endsection
