@extends('fsmcore::layouts.master')

@section('fsm_content')
@php
    $stateColors = [
        'draft'    => 'secondary',
        'progress' => 'success',
        'suspend'  => 'warning',
        'close'    => 'dark',
    ];
    $stateLabel = \Modules\FSMRecurring\Models\FSMRecurring::$states[$recurring->state] ?? $recurring->state;
@endphp

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2>{{ $recurring->name }}</h2>
        <span class="badge bg-{{ $stateColors[$recurring->state] ?? 'secondary' }} fs-6">{{ $stateLabel }}</span>
    </div>
    <div class="d-flex gap-2">
        @if($recurring->state === 'draft')
            <form method="POST" action="{{ route('fsmrecurring.recurring.start', $recurring->id) }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success">▶ Start</button>
            </form>
        @endif
        @if($recurring->state === 'progress')
            <form method="POST" action="{{ route('fsmrecurring.recurring.generate', $recurring->id) }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-primary">⟳ Generate Now</button>
            </form>
            <form method="POST" action="{{ route('fsmrecurring.recurring.suspend', $recurring->id) }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-warning">⏸ Suspend</button>
            </form>
            <form method="POST" action="{{ route('fsmrecurring.recurring.close', $recurring->id) }}" class="d-inline"
                  onsubmit="return confirm('Close this recurring schedule? No more orders will be generated.')">
                @csrf
                <button type="submit" class="btn btn-dark">✕ Close</button>
            </form>
        @endif
        @if($recurring->state === 'suspend')
            <form method="POST" action="{{ route('fsmrecurring.recurring.resume', $recurring->id) }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success">▶ Resume</button>
            </form>
            <form method="POST" action="{{ route('fsmrecurring.recurring.close', $recurring->id) }}" class="d-inline"
                  onsubmit="return confirm('Close this recurring schedule?')">
                @csrf
                <button type="submit" class="btn btn-dark">✕ Close</button>
            </form>
        @endif
        @if(in_array($recurring->state, ['draft', 'progress', 'suspend']))
            <a href="{{ route('fsmrecurring.recurring.edit', $recurring->id) }}" class="btn btn-outline-primary">Edit</a>
        @endif
        <a href="{{ route('fsmrecurring.recurring.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

<div class="row g-3">
    {{-- Details --}}
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header fw-semibold">Schedule Details</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Reference</dt><dd class="col-sm-7">{{ $recurring->name }}</dd>
                    <dt class="col-sm-5">State</dt>
                    <dd class="col-sm-7">
                        <span class="badge bg-{{ $stateColors[$recurring->state] ?? 'secondary' }}">{{ $stateLabel }}</span>
                    </dd>
                    <dt class="col-sm-5">Recurring Template</dt><dd class="col-sm-7">{{ $recurring->recurringTemplate?->name ?? '—' }}</dd>
                    <dt class="col-sm-5">Frequency Set</dt><dd class="col-sm-7">
                        @if($recurring->frequencySet)
                            <a href="{{ route('fsmrecurring.frequency-sets.edit', $recurring->frequency_set_id) }}">{{ $recurring->frequencySet->name }}</a>
                        @else —
                        @endif
                    </dd>
                    <dt class="col-sm-5">Order Template</dt><dd class="col-sm-7">{{ $recurring->fsmTemplate?->name ?? '—' }}</dd>
                    <dt class="col-sm-5">Location</dt><dd class="col-sm-7">{{ $recurring->location?->name ?? '—' }}</dd>
                    <dt class="col-sm-5">Team</dt><dd class="col-sm-7">{{ $recurring->team?->name ?? '—' }}</dd>
                    <dt class="col-sm-5">Assigned Worker</dt><dd class="col-sm-7">{{ $recurring->person?->name ?? '—' }}</dd>
                    <dt class="col-sm-5">Duration (h)</dt><dd class="col-sm-7">{{ $recurring->scheduled_duration ?? '—' }}</dd>
                    <dt class="col-sm-5">Start Date</dt><dd class="col-sm-7">{{ $recurring->start_date?->format('d M Y H:i') ?? '—' }}</dd>
                    <dt class="col-sm-5">End Date</dt><dd class="col-sm-7">{{ $recurring->end_date?->format('d M Y H:i') ?? '∞ Forever' }}</dd>
                    <dt class="col-sm-5">Max Orders</dt><dd class="col-sm-7">{{ $recurring->max_orders > 0 ? $recurring->max_orders : '∞ Unlimited' }}</dd>
                    <dt class="col-sm-5">Total Generated</dt>
                    <dd class="col-sm-7"><span class="badge bg-info text-dark">{{ $recurring->orders->count() }}</span></dd>
                    @if($recurring->description)
                    <dt class="col-sm-5">Description</dt><dd class="col-sm-7">{{ $recurring->description }}</dd>
                    @endif
                </dl>
            </div>
        </div>

        @if($recurring->equipment->isNotEmpty())
        <div class="card mb-3">
            <div class="card-header fw-semibold">Equipment</div>
            <div class="card-body">
                @foreach($recurring->equipment as $eq)
                    <span class="badge bg-info text-dark me-1">{{ $eq->name }}</span>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Orders chain --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
                <span>Generated Orders ({{ $recurring->orders->count() }})</span>
            </div>
            @if($recurring->orders->isEmpty())
                <div class="card-body text-muted">No orders generated yet.</div>
            @else
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Ref</th>
                            <th>Stage</th>
                            <th>Scheduled Start</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($recurring->orders->sortBy('scheduled_date_start') as $order)
                        <tr>
                            <td>
                                @if(class_exists(\Modules\FSMCore\Http\Controllers\OrderController::class))
                                    <a href="{{ route('fsmcore.orders.show', $order->id) }}">{{ $order->name }}</a>
                                @else
                                    {{ $order->name }}
                                @endif
                            </td>
                            <td>
                                @if($order->stage)
                                    <span class="badge" style="background:{{ $order->stage->color ?? '#6c757d' }}">{{ $order->stage->name }}</span>
                                @else —
                                @endif
                            </td>
                            <td>{{ $order->scheduled_date_start?->format('d M Y H:i') ?? '—' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
