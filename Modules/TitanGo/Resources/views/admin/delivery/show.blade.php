@extends('fsmcore::layouts.master')

@section('fsm_content')
@php
    $location   = $order->location;
    $worker     = $order->person;
    $stage      = $order->stage;
    $total      = $steps->count();
    $completed  = $steps->where('completed', true)->count();
    $allDone    = $completed === $total && $total > 0;

    // Try to resolve a customer email from the location's partner
    $partnerEmail = null;
    if ($location && $location->partner_id) {
        $partner = \App\Models\User::find($location->partner_id);
        $partnerEmail = $partner?->email;
    }
@endphp

{{-- ── Toolbar ──────────────────────────────────────────────────────────── --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="mb-0">Proof of Delivery</h2>
        <small class="text-muted">Visit #{{ $order->id }} — {{ $order->name }}</small>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('titango.admin.delivery.pdf', $order->id) }}"
           class="btn btn-outline-danger">
            <i class="bi bi-file-earmark-pdf me-1"></i> Download PDF
        </a>

        <button class="btn btn-outline-secondary" type="button"
                data-bs-toggle="collapse" data-bs-target="#emailForm">
            <i class="bi bi-envelope me-1"></i> Email to Customer
        </button>

        <a href="{{ class_exists(\Modules\FSMCore\Models\FSMOrder::class) ? route('fsmcore.orders.show', $order->id) : '#' }}"
           class="btn btn-outline-primary">
            ← Visit Detail
        </a>
    </div>
</div>

{{-- ── Email form (collapsible) ─────────────────────────────────────────── --}}
<div class="collapse mb-4 {{ session('success') ? '' : '' }}" id="emailForm">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card card-body">
        <form method="POST" action="{{ route('titango.admin.delivery.email', $order->id) }}" class="row g-2">
            @csrf
            <div class="col-md-6">
                <label class="form-label">Recipient email</label>
                <input type="email" name="recipient_email" class="form-control @error('recipient_email') is-invalid @enderror"
                       value="{{ old('recipient_email', $partnerEmail ?? '') }}"
                       placeholder="customer@example.com" required>
                @error('recipient_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-success w-100">
                    <i class="bi bi-send me-1"></i> Send PDF
                </button>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ── Report card ─────────────────────────────────────────────────────── --}}
<div class="card shadow-sm mb-4">
    <div class="card-body">

        {{-- Visit header -------------------------------------------------- --}}
        <div class="row g-3 mb-4 pb-3 border-bottom">
            <div class="col-md-4">
                <h5 class="fw-bold mb-1">{{ $order->name }}</h5>
                @if($location)
                    <p class="mb-0 text-muted">
                        {{ implode(', ', array_filter([
                            $location->street,
                            $location->city,
                            $location->state,
                            $location->zip,
                        ])) ?: $location->name }}
                    </p>
                @endif
            </div>
            <div class="col-md-2">
                <div class="text-muted small">Technician</div>
                <div class="fw-semibold">{{ $worker?->name ?? '—' }}</div>
            </div>
            <div class="col-md-2">
                <div class="text-muted small">Stage</div>
                <div class="fw-semibold">{{ $stage?->name ?? '—' }}</div>
            </div>
            <div class="col-md-2">
                <div class="text-muted small">Started</div>
                <div class="fw-semibold">{{ $order->date_start?->format('d M Y H:i') ?? '—' }}</div>
            </div>
            <div class="col-md-2">
                <div class="text-muted small">Finished</div>
                <div class="fw-semibold">{{ $order->date_end?->format('d M Y H:i') ?? '—' }}</div>
            </div>
        </div>

        {{-- Completion summary bar --------------------------------------- --}}
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="fw-semibold">Checklist completion</span>
                <span class="badge {{ $allDone ? 'bg-success' : 'bg-secondary' }}">
                    {{ $completed }} / {{ $total }} steps
                </span>
            </div>
            @if($total > 0)
                <div class="progress" style="height:8px">
                    <div class="progress-bar {{ $allDone ? 'bg-success' : 'bg-warning' }}"
                         style="width:{{ round($completed / $total * 100) }}%"></div>
                </div>
            @endif
        </div>

        {{-- Checklist table ---------------------------------------------- --}}
        <div class="table-responsive">
            <table class="table table-sm align-middle">
                <thead class="table-light">
                <tr>
                    <th style="width:40px">#</th>
                    <th>Step</th>
                    <th style="width:90px">Status</th>
                    <th>Completed at</th>
                    <th>Notes</th>
                    <th>Evidence</th>
                </tr>
                </thead>
                <tbody>
                @forelse($steps as $step)
                    <tr class="{{ $step['completed'] ? '' : 'table-warning' }}">
                        <td class="text-muted">{{ $step['index'] + 1 }}</td>
                        <td>
                            {{ $step['instruction'] }}
                            @if($step['photo_required'])
                                <span class="badge bg-light text-dark border ms-1" title="Photo required">📷</span>
                            @endif
                            @if($step['signature_required'])
                                <span class="badge bg-light text-dark border ms-1" title="Signature required">✍️</span>
                            @endif
                            @if($step['is_required'])
                                <span class="badge bg-light text-danger border ms-1" title="Required step">Req</span>
                            @endif
                        </td>
                        <td>
                            @if($step['completed'])
                                <span class="badge bg-success">✓ Done</span>
                            @else
                                <span class="badge bg-secondary">—</span>
                            @endif
                        </td>
                        <td class="small text-muted">
                            {{ $step['completed_at'] ? \Carbon\Carbon::parse($step['completed_at'])->format('d M H:i') : '—' }}
                        </td>
                        <td class="small">{{ $step['notes'] ?? '—' }}</td>
                        <td>
                            <div class="d-flex gap-2 flex-wrap">
                                @if($step['photo_data'])
                                    <a href="{{ $step['photo_data'] }}" target="_blank">
                                        <img src="{{ $step['photo_data'] }}"
                                             alt="Photo"
                                             class="rounded border"
                                             style="height:48px;width:48px;object-fit:cover">
                                    </a>
                                @endif
                                @if($step['signature_data'])
                                    <img src="{{ $step['signature_data'] }}"
                                         alt="Signature"
                                         class="border rounded bg-white"
                                         style="height:48px;max-width:120px;object-fit:contain">
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No checklist steps for this visit.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <div class="card-footer text-muted small d-flex justify-content-between">
        <span>Generated {{ now()->format('d M Y H:i') }} UTC</span>
        <span>Titan Go — Worksuite</span>
    </div>
</div>
@endsection
