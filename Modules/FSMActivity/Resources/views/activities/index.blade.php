@extends('fsmactivity::layouts.master')

@section('fsmactivity_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Activities – Order: {{ $order->name }}</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmactivity.activities.create', $order->id) }}" class="btn btn-success">+ Log Activity</a>
        <a href="{{ route('fsmactivity.global.index') }}" class="btn btn-outline-secondary">All Activities</a>
        <a href="{{ route('fsmcore.orders.show', $order->id) }}" class="btn btn-outline-secondary">Back to Order</a>
    </div>
</div>

@php use Modules\FSMActivity\Models\FSMActivity; @endphp

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Type</th>
                    <th>Summary</th>
                    <th>Due Date</th>
                    <th>Assigned To</th>
                    <th>State</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities as $act)
                @php
                    $rowClass  = $act->isOverdue() ? 'table-danger' : '';
                    $badgeClass = match($act->state) {
                        'done'      => 'bg-success',
                        'cancelled' => 'bg-secondary',
                        'overdue'   => 'bg-danger',
                        default     => 'bg-primary',
                    };
                @endphp
                <tr class="{{ $rowClass }}">
                    <td>{{ $act->activityType?->name ?? '—' }}</td>
                    <td>{{ $act->summary ?? '—' }}</td>
                    <td>{{ $act->due_date?->format('d M Y') ?? '—' }}</td>
                    <td>{{ $act->assignedUser?->name ?? '—' }}</td>
                    <td><span class="badge {{ $badgeClass }}">{{ FSMActivity::STATES[$act->state] ?? $act->state }}</span></td>
                    <td>
                        @if(in_array($act->state, ['open', 'overdue']))
                            <form method="POST" action="{{ route('fsmactivity.activities.done', [$order->id, $act->id]) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-success" title="Mark Done">✔ Done</button>
                            </form>
                            <form method="POST" action="{{ route('fsmactivity.activities.cancel', [$order->id, $act->id]) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-warning" title="Cancel">Cancel</button>
                            </form>
                            <a href="{{ route('fsmactivity.activities.edit', [$order->id, $act->id]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        @endif
                        <form method="POST" action="{{ route('fsmactivity.activities.destroy', [$order->id, $act->id]) }}" class="d-inline"
                              onsubmit="return confirm('Delete this activity?')">
                            @csrf
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-muted text-center py-4">No activities logged for this order.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
