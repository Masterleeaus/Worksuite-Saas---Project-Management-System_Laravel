@extends('fsmserviceagreement::layouts.master')

@section('fsm_content')
@php $sl = $agreement->stateLabel(); @endphp

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Agreement: {{ $agreement->name }}</h2>
    <div class="d-flex gap-2 flex-wrap">
        {{-- State actions --}}
        @if($agreement->isDraft())
            <form method="POST" action="{{ route('fsmserviceagreement.agreements.activate', $agreement->id) }}">
                @csrf
                <button type="submit" class="btn btn-success"
                        onclick="return confirm('Activate this agreement and generate job orders?')">
                    Activate & Generate Orders
                </button>
            </form>
        @endif

        @if($agreement->isActive() || $agreement->isDraft())
            <form method="POST" action="{{ route('fsmserviceagreement.agreements.cancel', $agreement->id) }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger"
                        onclick="return confirm('Cancel this agreement?')">
                    Cancel Agreement
                </button>
            </form>
        @endif

        <a href="{{ route('fsmserviceagreement.agreements.edit', $agreement->id) }}" class="btn btn-primary">Edit</a>
        <a href="{{ route('fsmserviceagreement.agreements.index') }}" class="btn btn-outline-secondary">← Back</a>
    </div>
</div>

@if($agreement->isExpiringSoon())
    <div class="alert alert-warning">
        ⚠️ This agreement expires on <strong>{{ $agreement->end_date->format('d M Y') }}</strong>
        ({{ $agreement->end_date->diffForHumans() }}).
    </div>
@endif

<div class="row g-3">
    {{-- Main details --}}
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header fw-semibold">Agreement Details</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Reference</dt><dd class="col-sm-8">{{ $agreement->name }}</dd>
                    <dt class="col-sm-4">Status</dt>
                    <dd class="col-sm-8"><span class="badge {{ $sl['class'] }}">{{ $sl['label'] }}</span></dd>
                    <dt class="col-sm-4">Client</dt><dd class="col-sm-8">{{ $agreement->client?->name ?? '—' }}</dd>
                    <dt class="col-sm-4">Start Date</dt><dd class="col-sm-8">{{ $agreement->start_date?->format('d M Y') ?? '—' }}</dd>
                    <dt class="col-sm-4">End Date</dt>
                    <dd class="col-sm-8">
                        {{ $agreement->end_date?->format('d M Y') ?? 'Ongoing' }}
                    </dd>
                    <dt class="col-sm-4">Contract Value</dt><dd class="col-sm-8">${{ number_format($agreement->value, 2) }}</dd>
                    <dt class="col-sm-4">Notes</dt><dd class="col-sm-8">{{ $agreement->notes ?? '—' }}</dd>
                    <dt class="col-sm-4">Recurrence Rule</dt>
                    <dd class="col-sm-8">
                        @if($agreement->recurrence_rule)
                            <pre class="mb-0 small">{{ json_encode($agreement->recurrence_rule, JSON_PRETTY_PRINT) }}</pre>
                        @else
                            —
                        @endif
                    </dd>
                </dl>
            </div>
        </div>

        {{-- Sites --}}
        <div class="card mb-3">
            <div class="card-header fw-semibold">Sites / Locations ({{ $agreement->locations->count() }})</div>
            <div class="card-body">
                @forelse($agreement->locations as $loc)
                    <span class="badge bg-info text-dark me-1 mb-1">{{ $loc->name }}</span>
                    @if($loc->city)<small class="text-muted me-2">{{ $loc->city }}</small>@endif
                @empty
                    <span class="text-muted">No sites linked.</span>
                @endforelse
            </div>
        </div>

        {{-- Templates --}}
        <div class="card mb-3">
            <div class="card-header fw-semibold">Job Templates ({{ $agreement->templates->count() }})</div>
            <div class="card-body">
                @forelse($agreement->templates as $tpl)
                    <span class="badge bg-secondary me-1 mb-1">{{ $tpl->name }}</span>
                @empty
                    <span class="text-muted">No templates linked.</span>
                @endforelse
            </div>
        </div>

        {{-- Line Items --}}
        @if($agreement->lines->isNotEmpty())
        <div class="card mb-3">
            <div class="card-header fw-semibold">Line Items</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>Service</th>
                        <th>Site</th>
                        <th>Frequency</th>
                        <th class="text-end">Unit Price</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($agreement->lines as $line)
                        <tr>
                            <td>{{ $line->service_description }}</td>
                            <td>{{ $line->location?->name ?? 'All sites' }}</td>
                            <td>{{ $line->frequency ?? '—' }}</td>
                            <td class="text-end">${{ number_format($line->unit_price, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    {{-- Order History --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header fw-semibold">
                Order History
                <span class="badge bg-secondary ms-1">{{ $agreement->orders->count() }}</span>
            </div>
            <div class="card-body p-0">
                @if($agreement->orders->isEmpty())
                    <p class="text-muted p-3 mb-0">No orders generated yet.</p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($agreement->orders->sortByDesc('id') as $order)
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div>
                                    <a href="{{ route('fsmcore.orders.show', $order->id) }}" class="fw-semibold">
                                        {{ $order->name }}
                                    </a>
                                    <br>
                                    <small class="text-muted">
                                        {{ $order->location?->name ?? '—' }}
                                        @if($order->scheduled_date_start)
                                            · {{ $order->scheduled_date_start->format('d M Y') }}
                                        @endif
                                    </small>
                                </div>
                                @if($order->stage)
                                    <span class="badge ms-2" style="background:{{ $order->stage->color ?? '#6c757d' }};">
                                        {{ $order->stage->name }}
                                    </span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
