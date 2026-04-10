@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>FSM Templates</h2>
    <a href="{{ route('fsmcore.templates.create') }}" class="btn btn-success">+ New Template</a>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr><th>Name</th><th>Est. Duration</th><th>Active</th><th>Actions</th></tr>
        </thead>
        <tbody>
        @forelse($templates as $tpl)
            <tr>
                <td>{{ $tpl->name }}</td>
                <td>{{ $tpl->estimated_duration_minutes ? $tpl->estimated_duration_minutes.' min' : '—' }}</td>
                <td>{{ $tpl->active ? '✅' : '❌' }}</td>
                <td>
                    <a href="{{ route('fsmcore.templates.edit', $tpl->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="{{ route('fsmcore.templates.destroy', $tpl->id) }}" class="d-inline" onsubmit="return confirm('Delete?')">
                        @csrf <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted py-4">No templates yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $templates->links() }}
@endsection
