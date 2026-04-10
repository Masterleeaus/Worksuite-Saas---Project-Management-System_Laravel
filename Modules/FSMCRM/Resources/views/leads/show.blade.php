@extends('fsmcrm::layouts.master')

@section('fsmcrm_content')
@php
    $stageColors = ['new' => 'secondary', 'qualified' => 'info', 'won' => 'success', 'lost' => 'danger'];
    $stageBadge  = $stageColors[$lead->stage] ?? 'secondary';
    $stageLabel  = \Modules\FSMCRM\Models\FSMLead::stages()[$lead->stage] ?? $lead->stage;
@endphp

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>{{ $lead->name }}</h2>
    <div class="d-flex gap-2 flex-wrap">
        @if($lead->isWon())
            <a href="{{ route('fsmcrm.leads.convert', $lead->id) }}" class="btn btn-success">🔧 Create FSM Order</a>
        @endif
        <a href="{{ route('fsmcrm.leads.edit', $lead->id) }}" class="btn btn-primary">Edit</a>
        <a href="{{ route('fsmcrm.leads.index') }}" class="btn btn-outline-secondary">← Back</a>
    </div>
</div>

<div class="row g-3">
    {{-- Lead details --}}
    <div class="col-md-7">
        <div class="card mb-3">
            <div class="card-header fw-semibold">Lead Details</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Stage</dt>
                    <dd class="col-sm-8"><span class="badge bg-{{ $stageBadge }}">{{ $stageLabel }}</span></dd>

                    <dt class="col-sm-4">Contact</dt>
                    <dd class="col-sm-8">{{ $lead->contact_name ?? '—' }}</dd>

                    <dt class="col-sm-4">Email</dt>
                    <dd class="col-sm-8">{{ $lead->email ?? '—' }}</dd>

                    <dt class="col-sm-4">Phone</dt>
                    <dd class="col-sm-8">{{ $lead->phone ?? '—' }}</dd>

                    <dt class="col-sm-4">Expected Revenue</dt>
                    <dd class="col-sm-8">{{ $lead->expected_revenue > 0 ? number_format($lead->expected_revenue, 2) : '—' }}</dd>

                    <dt class="col-sm-4">Close Date</dt>
                    <dd class="col-sm-8">{{ $lead->close_date?->format('d M Y') ?? '—' }}</dd>

                    <dt class="col-sm-4">Notes</dt>
                    <dd class="col-sm-8">{!! nl2br(e($lead->notes ?? '—')) !!}</dd>
                </dl>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header fw-semibold">FSM Details</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">FSM Location</dt>
                    <dd class="col-sm-8">
                        @if($lead->fsmLocation)
                            <a href="{{ route('fsmcore.locations.show', $lead->fsmLocation->id) }}">{{ $lead->fsmLocation->name }}</a>
                        @else
                            —
                        @endif
                    </dd>

                    <dt class="col-sm-4">Service Type</dt>
                    <dd class="col-sm-8">{{ $lead->serviceType?->name ?? '—' }}</dd>

                    <dt class="col-sm-4">Number of Sites</dt>
                    <dd class="col-sm-8">{{ $lead->site_count }}</dd>

                    <dt class="col-sm-4">Est. Hours / Visit</dt>
                    <dd class="col-sm-8">{{ $lead->estimated_hours ? $lead->estimated_hours . 'h' : '—' }}</dd>

                    <dt class="col-sm-4">Recurring Contract?</dt>
                    <dd class="col-sm-8">
                        @if($lead->create_recurring)
                            <span class="badge bg-info">Yes</span>
                        @else
                            <span class="badge bg-secondary">No</span>
                        @endif
                    </dd>
                </dl>
            </div>
        </div>
    </div>

    {{-- FSM Orders smart button / panel --}}
    <div class="col-md-5">
        <div class="card">
            <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
                <span>🗂 FSM Orders</span>
                <span class="badge bg-primary rounded-pill">{{ $orders->count() }}</span>
            </div>
            <div class="card-body p-0">
                @forelse($orders as $order)
                    <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                        <div>
                            <a href="{{ route('fsmcore.orders.show', $order->id) }}" class="fw-semibold text-decoration-none">{{ $order->name }}</a>
                            <div class="small text-muted">{{ $order->location?->name ?? '—' }}</div>
                        </div>
                        <div>
                            @if($order->stage)
                                <span class="badge" style="background:{{ $order->stage->color ?? '#6c757d' }};">{{ $order->stage->name }}</span>
                            @else
                                <span class="badge bg-secondary">—</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-3 py-3 text-muted text-center">No FSM Orders yet.</div>
                @endforelse
            </div>
            @if($lead->isWon())
                <div class="card-footer">
                    <a href="{{ route('fsmcrm.leads.convert', $lead->id) }}" class="btn btn-success btn-sm w-100">+ Create FSM Order</a>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="mt-3">
    <form method="POST" action="{{ route('fsmcrm.leads.destroy', $lead->id) }}"
          onsubmit="return confirm('Delete this lead?')">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-danger">Delete Lead</button>
    </form>
</div>
@endsection
