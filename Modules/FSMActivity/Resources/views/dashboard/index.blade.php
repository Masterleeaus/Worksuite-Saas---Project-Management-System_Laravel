@extends('fsmactivity::layouts.master')

@section('fsmactivity_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Activities Dashboard</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmactivity.global.index') }}" class="btn btn-outline-secondary">All Activities</a>
        <a href="{{ route('fsmactivity.types.index') }}" class="btn btn-outline-secondary">Activity Types</a>
    </div>
</div>

{{-- Stat cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">Due Today</h5>
                <p class="card-text display-6">{{ $dueTodayCount }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h5 class="card-title">Overdue</h5>
                <p class="card-text display-6">{{ $overdueCount }}</p>
                <a href="{{ route('fsmactivity.global.index', ['state' => 'overdue']) }}" class="text-white small">View all →</a>
            </div>
        </div>
    </div>
</div>

{{-- Activities due today --}}
<h4>Activities Due Today</h4>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Order</th>
                    <th>Type</th>
                    <th>Summary</th>
                    <th>Assigned To</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dueToday as $act)
                <tr>
                    <td>
                        @if($act->order)
                            <a href="{{ route('fsmactivity.activities.index', $act->order->id) }}">{{ $act->order->name }}</a>
                        @else —
                        @endif
                    </td>
                    <td>{{ $act->activityType?->name ?? '—' }}</td>
                    <td>{{ $act->summary ?? '—' }}</td>
                    <td>{{ $act->assignedUser?->name ?? '—' }}</td>
                    <td>
                        @if($act->order)
                            <form method="POST" action="{{ route('fsmactivity.activities.done', [$act->order->id, $act->id]) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-success">✔ Done</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-muted text-center py-4">No activities due today.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
