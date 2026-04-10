@extends('fsmactivity::layouts.master')

@section('fsmactivity_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>All Activities</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmactivity.dashboard') }}" class="btn btn-outline-warning">Dashboard</a>
        <a href="{{ route('fsmactivity.types.index') }}" class="btn btn-outline-secondary">Activity Types</a>
    </div>
</div>

{{-- Filter form --}}
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('fsmactivity.global.index') }}" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small">Type</label>
                <select name="type_id" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ request('type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Assigned To</label>
                <select name="assigned_to" class="form-select form-select-sm">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">State</label>
                <select name="state" class="form-select form-select-sm">
                    <option value="">All States</option>
                    @foreach(\Modules\FSMActivity\Models\FSMActivity::STATES as $val => $label)
                        <option value="{{ $val }}" {{ request('state') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Due From</label>
                <input type="date" name="due_date_from" class="form-control form-control-sm" value="{{ request('due_date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Due To</label>
                <input type="date" name="due_date_to" class="form-control form-control-sm" value="{{ request('due_date_to') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Order</th>
                    <th>Type</th>
                    <th>Summary</th>
                    <th>Due Date</th>
                    <th>Assigned To</th>
                    <th>State</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities as $act)
                @php
                    $badgeClass = match($act->state) {
                        'done'      => 'bg-success',
                        'cancelled' => 'bg-secondary',
                        'overdue'   => 'bg-danger',
                        default     => 'bg-primary',
                    };
                @endphp
                <tr class="{{ $act->isOverdue() ? 'table-danger' : '' }}">
                    <td>
                        @if($act->order)
                            <a href="{{ route('fsmactivity.activities.index', $act->order->id) }}">{{ $act->order->name }}</a>
                        @else —
                        @endif
                    </td>
                    <td>{{ $act->activityType?->name ?? '—' }}</td>
                    <td>{{ $act->summary ?? '—' }}</td>
                    <td>{{ $act->due_date?->format('d M Y') ?? '—' }}</td>
                    <td>{{ $act->assignedUser?->name ?? '—' }}</td>
                    <td><span class="badge {{ $badgeClass }}">{{ \Modules\FSMActivity\Models\FSMActivity::STATES[$act->state] ?? $act->state }}</span></td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-muted text-center py-4">No activities found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $activities->links() }}</div>
@endsection
