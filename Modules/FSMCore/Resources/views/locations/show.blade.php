@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>{{ $location->name }}</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmcore.locations.edit', $location->id) }}" class="btn btn-primary">Edit</a>
        <a href="{{ route('fsmcore.locations.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header fw-semibold">Location Details</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Name</dt><dd class="col-sm-8">{{ $location->name }}</dd>
                    <dt class="col-sm-4">Territory</dt><dd class="col-sm-8">{{ $location->territory?->name ?? '—' }}</dd>
                    <dt class="col-sm-4">Street</dt><dd class="col-sm-8">{{ $location->street ?? '—' }}</dd>
                    <dt class="col-sm-4">City</dt><dd class="col-sm-8">{{ $location->city ?? '—' }}</dd>
                    <dt class="col-sm-4">State</dt><dd class="col-sm-8">{{ $location->state ?? '—' }}</dd>
                    <dt class="col-sm-4">ZIP</dt><dd class="col-sm-8">{{ $location->zip ?? '—' }}</dd>
                    <dt class="col-sm-4">Country</dt><dd class="col-sm-8">{{ $location->country ?? '—' }}</dd>
                    <dt class="col-sm-4">GPS</dt><dd class="col-sm-8">{{ $location->latitude ? $location->latitude.', '.$location->longitude : '—' }}</dd>
                    <dt class="col-sm-4">Notes</dt><dd class="col-sm-8">{{ $location->notes ?? '—' }}</dd>
                    <dt class="col-sm-4">Active</dt><dd class="col-sm-8">{{ $location->active ? 'Yes' : 'No' }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header fw-semibold">Orders at this Location ({{ $location->orders->count() }})</div>
            <div class="card-body">
                @forelse($location->orders->take(10) as $order)
                    <div><a href="{{ route('fsmcore.orders.show', $order->id) }}">{{ $order->name }}</a></div>
                @empty
                    <span class="text-muted">No orders.</span>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
