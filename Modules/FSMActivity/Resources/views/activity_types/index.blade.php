@extends('fsmactivity::layouts.master')

@section('fsmactivity_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Activity Types</h2>
    <a href="{{ route('fsmactivity.types.create') }}" class="btn btn-success">+ New Activity Type</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Icon</th>
                    <th>Delay</th>
                    <th>Default User</th>
                    <th>Summary</th>
                    <th>Active</th>
                    <th>Activities</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($types as $type)
                <tr>
                    <td>{{ $type->name }}</td>
                    <td>{{ $type->icon ?? '—' }}</td>
                    <td>{{ $type->delay_count }} {{ $type->delay_unit }}</td>
                    <td>{{ $type->defaultUser?->name ?? '—' }}</td>
                    <td>{{ $type->summary ?? '—' }}</td>
                    <td>
                        @if($type->active)
                            <span class="badge bg-success">Yes</span>
                        @else
                            <span class="badge bg-secondary">No</span>
                        @endif
                    </td>
                    <td><span class="badge bg-info text-dark">{{ $type->activities_count }}</span></td>
                    <td>
                        <a href="{{ route('fsmactivity.types.edit', $type->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form method="POST" action="{{ route('fsmactivity.types.destroy', $type->id) }}" class="d-inline"
                              onsubmit="return confirm('Delete this activity type?')">
                            @csrf
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-muted text-center py-4">No activity types found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $types->links() }}</div>
@endsection
