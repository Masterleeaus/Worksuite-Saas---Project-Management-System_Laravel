@extends('fsmrouteavailability::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>FSM Blackout Days</h2>
    <a href="{{ route('fsmrouteavailability.days.create') }}" class="btn btn-success">+ New Blackout Day</a>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <select name="group_id" class="form-select">
            <option value="">All Groups</option>
            @foreach($groups as $group)
                <option value="{{ $group->id }}" @selected(request('group_id') == $group->id)>{{ $group->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary">Filter</button>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>Group</th>
            <th>Date</th>
            <th>Label</th>
            <th>Zip</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($days as $day)
            <tr>
                <td>{{ $day->group?->name ?? '—' }}</td>
                <td>{{ $day->date->format('Y-m-d') }}</td>
                <td>{{ $day->label ?: '—' }}</td>
                <td>{{ $day->zip ?: '—' }}</td>
                <td>
                    <a href="{{ route('fsmrouteavailability.days.edit', $day->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="{{ route('fsmrouteavailability.days.destroy', $day->id) }}" class="d-inline" onsubmit="return confirm('Delete this blackout day?')">
                        @csrf
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted py-4">No blackout days found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $days->links() }}
@endsection
