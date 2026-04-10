@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>FSM Orders</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmcore.orders.kanban') }}" class="btn btn-outline-primary">Kanban</a>
        <a href="{{ route('fsmcore.orders.create') }}" class="btn btn-success">+ New Order</a>
    </div>
</div>

{{-- Filters --}}
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <input type="text" name="q" class="form-control" placeholder="Search…" value="{{ $filter['q'] ?? '' }}">
    </div>
    <div class="col-md-2">
        <select name="stage_id" class="form-select">
            <option value="">All Stages</option>
            @foreach($stages as $stage)
                <option value="{{ $stage->id }}" {{ ($filter['stage_id'] ?? '') == $stage->id ? 'selected' : '' }}>{{ $stage->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <select name="team_id" class="form-select">
            <option value="">All Teams</option>
            @foreach($teams as $team)
                <option value="{{ $team->id }}" {{ ($filter['team_id'] ?? '') == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <select name="priority" class="form-select">
            <option value="">Any Priority</option>
            <option value="0" {{ ($filter['priority'] ?? '') === '0' ? 'selected' : '' }}>Normal</option>
            <option value="1" {{ ($filter['priority'] ?? '') === '1' ? 'selected' : '' }}>Urgent</option>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>Ref</th>
            <th>Location</th>
            <th>Worker</th>
            <th>Team</th>
            <th>Stage</th>
            <th>Priority</th>
            <th>Scheduled Start</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($orders as $order)
            <tr>
                <td><a href="{{ route('fsmcore.orders.show', $order->id) }}">{{ $order->name }}</a></td>
                <td>{{ $order->location?->name ?? '—' }}</td>
                <td>{{ $order->person?->name ?? '—' }}</td>
                <td>{{ $order->team?->name ?? '—' }}</td>
                <td>
                    @if($order->stage)
                        <span class="badge" style="background:{{ $order->stage->color ?? '#6c757d' }};">{{ $order->stage->name }}</span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    @if($order->priority === '1')
                        <span class="badge bg-danger">Urgent</span>
                    @else
                        <span class="badge bg-secondary">Normal</span>
                    @endif
                </td>
                <td>{{ $order->scheduled_date_start?->format('d M Y H:i') ?? '—' }}</td>
                <td>
                    <a href="{{ route('fsmcore.orders.edit', $order->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="{{ route('fsmcore.orders.destroy', $order->id) }}" class="d-inline" onsubmit="return confirm('Delete this order?')">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="8" class="text-center text-muted py-4">No orders found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

{{ $orders->links() }}
@endsection
