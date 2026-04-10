@extends('fsmsales::layouts.master')

@section('fsmsales_content')
@php $sl = $entry->statusLabel(); @endphp

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Recurring Invoice #{{ $entry->id }}</h2>
    <div class="d-flex gap-2">
        @if($entry->status === 'draft')
        <form method="POST" action="{{ route('fsmsales.recurring.convert', $entry->id) }}">
            @csrf
            <button class="btn btn-primary">Convert to Invoice</button>
        </form>
        @endif
        @if(!in_array($entry->status, ['paid']))
        <form method="POST" action="{{ route('fsmsales.recurring.mark-paid', $entry->id) }}">
            @csrf
            <button class="btn btn-success">Mark Paid</button>
        </form>
        @endif
        <a href="{{ route('fsmsales.recurring.index') }}" class="btn btn-outline-secondary">← Recurring</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header fw-semibold d-flex justify-content-between">
                Recurring Entry Details
                <span class="badge {{ $sl['class'] }}">{{ $sl['label'] }}</span>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Client</dt><dd class="col-sm-7">{{ $entry->client?->name ?? '—' }}</dd>
                    <dt class="col-sm-5">Schedule</dt><dd class="col-sm-7">{{ $entry->scheduleLabel() }}</dd>
                    <dt class="col-sm-5">Period</dt>
                    <dd class="col-sm-7">{{ $entry->period_start?->format('d M Y') ?? '—' }} → {{ $entry->period_end?->format('d M Y') ?? '—' }}</dd>
                    <dt class="col-sm-5">Amount</dt><dd class="col-sm-7 fw-semibold">${{ number_format($entry->amount, 2) }}</dd>
                    <dt class="col-sm-5">Due Date</dt>
                    <dd class="col-sm-7 {{ $entry->isOverdue() ? 'text-danger fw-semibold' : '' }}">
                        {{ $entry->due_date?->format('d M Y') ?? '—' }}
                        @if($entry->isOverdue())
                            <span class="badge bg-danger ms-1">OVERDUE</span>
                        @endif
                    </dd>
                    @if($entry->notes)
                    <dt class="col-sm-5">Notes</dt><dd class="col-sm-7">{{ $entry->notes }}</dd>
                    @endif
                    @if($entry->agreement)
                    <dt class="col-sm-5">Agreement</dt>
                    <dd class="col-sm-7">
                        <a href="{{ route('fsmserviceagreement.agreements.show', $entry->agreement->id) }}">{{ $entry->agreement->name }}</a>
                    </dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    @if($entry->invoice)
    <div class="col-md-6">
        <div class="card">
            <div class="card-header fw-semibold">Linked Invoice</div>
            <div class="card-body">
                <a href="{{ route('fsmsales.invoices.show', $entry->invoice->id) }}" class="btn btn-outline-primary">
                    {{ $entry->invoice->number }}
                </a>
                @php $isl = $entry->invoice->statusLabel(); @endphp
                <span class="badge {{ $isl['class'] }} ms-2">{{ $isl['label'] }}</span>
                <div class="mt-2 small text-muted">Total: ${{ number_format($entry->invoice->total, 2) }}</div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
