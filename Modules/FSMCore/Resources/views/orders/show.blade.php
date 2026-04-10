@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Order: {{ $order->name }}</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmcore.orders.edit', $order->id) }}" class="btn btn-primary">Edit</a>
        <a href="{{ route('fsmcore.orders.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

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
