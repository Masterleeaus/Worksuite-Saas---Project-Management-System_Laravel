@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Frequency Rules</h2>
    <a href="{{ route('fsmrecurring.frequencies.create') }}" class="btn btn-success">+ New Rule</a>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>Name</th>
            <th>Interval</th>
            <th>Type</th>
            <th>Exclusive</th>
            <th>Active</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($frequencies as $freq)
            <tr>
                <td>{{ $freq->name }}</td>
                <td>{{ $freq->interval }}</td>
                <td><span class="badge bg-secondary text-capitalize">{{ $freq->interval_type }}</span></td>
                <td>{{ $freq->is_exclusive ? '✓ Exclusive' : '—' }}</td>
                <td>
                    @if($freq->active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('fsmrecurring.frequencies.edit', $freq->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="{{ route('fsmrecurring.frequencies.destroy', $freq->id) }}" class="d-inline"
                          onsubmit="return confirm('Delete this frequency rule?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted py-4">No frequency rules found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

{{ $frequencies->links() }}
@endsection
