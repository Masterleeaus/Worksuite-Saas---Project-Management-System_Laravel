@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Frequency Sets</h2>
    <a href="{{ route('fsmrecurring.frequency-sets.create') }}" class="btn btn-success">+ New Set</a>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>Name</th>
            <th>Rules</th>
            <th>Schedule Days</th>
            <th>Buffer Early</th>
            <th>Buffer Late</th>
            <th>Active</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($sets as $set)
            <tr>
                <td>{{ $set->name }}</td>
                <td><span class="badge bg-info text-dark">{{ $set->frequencies_count }}</span></td>
                <td>{{ $set->schedule_days }} days</td>
                <td>{{ $set->buffer_early }} days</td>
                <td>{{ $set->buffer_late }} days</td>
                <td>
                    @if($set->active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('fsmrecurring.frequency-sets.edit', $set->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="{{ route('fsmrecurring.frequency-sets.destroy', $set->id) }}" class="d-inline"
                          onsubmit="return confirm('Delete this frequency set?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center text-muted py-4">No frequency sets found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

{{ $sets->links() }}
@endsection
