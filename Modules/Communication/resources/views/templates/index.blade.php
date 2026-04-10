@extends('communication::layouts.master')

@section('communication_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">
        <i class="bi bi-file-text me-2"></i>Message Templates
    </h2>
    <a href="{{ route('communications.templates.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>New Template
    </a>
</div>

{{-- Filters --}}
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-2">
        <select name="type" class="form-select form-select-sm">
            <option value="">All Types</option>
            @foreach($types as $key => $label)
                <option value="{{ $key }}" {{ ($filter['type'] ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <input type="text" name="q" class="form-control form-control-sm" placeholder="Search templates…" value="{{ $filter['q'] ?? '' }}">
    </div>
    <div class="col-auto">
        <button class="btn btn-sm btn-secondary">Filter</button>
        <a href="{{ route('communications.templates.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
    </div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Subject</th>
                    <th>Variables</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($templates as $tpl)
                <tr>
                    <td>{{ $tpl->name }}</td>
                    <td>{{ $types[$tpl->type] ?? ucfirst($tpl->type) }}</td>
                    <td class="text-truncate" style="max-width:200px;">{{ $tpl->subject ?: '—' }}</td>
                    <td>
                        @if($tpl->variables)
                            @foreach($tpl->variables as $var)
                                <span class="badge bg-light text-dark border">{!! '{' . $var . '}' !!}</span>
                            @endforeach
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-{{ $tpl->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($tpl->status) }}</span>
                    </td>
                    <td>
                        <a href="{{ route('communications.templates.edit', $tpl->id) }}" class="btn btn-xs btn-outline-primary btn-sm">Edit</a>
                        <form method="POST" action="{{ route('communications.templates.destroy', $tpl->id) }}" class="d-inline" onsubmit="return confirm('Delete this template?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-outline-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">No templates found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($templates->hasPages())
    <div class="card-footer">
        {{ $templates->links() }}
    </div>
    @endif
</div>
@endsection
