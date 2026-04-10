@extends('fsmavailability::layouts.master')

@section('fsmavailability_content')
@php
    $stateClass = match($exception->state) {
        'approved' => 'success',
        'rejected' => 'danger',
        default    => 'warning',
    };
@endphp

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>
        Availability Exception
        <span class="badge bg-{{ $stateClass }} ms-2">
            {{ $states[$exception->state] ?? $exception->state }}
        </span>
    </h2>
    <div class="d-flex gap-2">
        @if($exception->state === 'pending')
            <form method="POST" action="{{ route('fsmavailability.exceptions.approve', $exception->id) }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success">✔ Approve</button>
            </form>
            <form method="POST" action="{{ route('fsmavailability.exceptions.reject', $exception->id) }}" class="d-inline"
                  onsubmit="return confirm('Reject this exception?')">
                @csrf
                <button type="submit" class="btn btn-danger">✘ Reject</button>
            </form>
        @endif
        <a href="{{ route('fsmavailability.exceptions.edit', $exception->id) }}" class="btn btn-outline-primary">Edit</a>
        <form method="POST" action="{{ route('fsmavailability.exceptions.destroy', $exception->id) }}" class="d-inline"
              onsubmit="return confirm('Delete this exception permanently?')">
            @csrf
            <button type="submit" class="btn btn-outline-danger">Delete</button>
        </form>
        <a href="{{ route('fsmavailability.exceptions.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3">Worker</dt>
            <dd class="col-sm-9">{{ $exception->person?->name ?? '—' }}</dd>

            <dt class="col-sm-3">From</dt>
            <dd class="col-sm-9">{{ $exception->date_start->format('d M Y H:i') }}</dd>

            <dt class="col-sm-3">To</dt>
            <dd class="col-sm-9">{{ $exception->date_end->format('d M Y H:i') }}</dd>

            <dt class="col-sm-3">Reason</dt>
            <dd class="col-sm-9">{{ $reasons[$exception->reason] ?? $exception->reason }}</dd>

            <dt class="col-sm-3">Notes</dt>
            <dd class="col-sm-9">{{ $exception->notes ?: '—' }}</dd>

            <dt class="col-sm-3">State</dt>
            <dd class="col-sm-9">
                <span class="badge bg-{{ $stateClass }}">{{ $states[$exception->state] ?? $exception->state }}</span>
            </dd>

            @if($exception->approvedBy)
            <dt class="col-sm-3">Approved By</dt>
            <dd class="col-sm-9">{{ $exception->approvedBy->name }}</dd>
            @endif
        </dl>
    </div>
</div>
@endsection
