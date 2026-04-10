@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>FSM Stages</h2>
    <a href="{{ route('fsmcore.stages.create') }}" class="btn btn-success">+ New Stage</a>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr><th>Seq</th><th>Name</th><th>Completion Stage</th><th>Color</th><th>Orders</th><th>Actions</th></tr>
        </thead>
        <tbody>
        @forelse($stages as $stage)
            <tr>
                <td>{{ $stage->sequence }}</td>
                <td><span class="badge" style="background:{{ $stage->color ?? '#6c757d' }};">{{ $stage->name }}</span></td>
                <td>{{ $stage->is_completion_stage ? '✅' : '—' }}</td>
                <td><code>{{ $stage->color ?? '—' }}</code></td>
                <td>{{ $stage->orders_count }}</td>
                <td>
                    <a href="{{ route('fsmcore.stages.edit', $stage->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="{{ route('fsmcore.stages.destroy', $stage->id) }}" class="d-inline" onsubmit="return confirm('Delete?')">
                        @csrf <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted py-4">No stages yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
