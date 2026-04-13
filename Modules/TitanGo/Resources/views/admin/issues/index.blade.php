@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Titan Go — Issue Reports</h2>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Filters --}}
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <select name="type" class="form-select">
            <option value="">All Types</option>
            @foreach($types as $type)
                <option value="{{ $type }}" {{ ($filter['type'] ?? '') === $type ? 'selected' : '' }}>
                    {{ str_replace('_', ' ', ucfirst($type)) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <select name="status" class="form-select">
            <option value="">All Statuses</option>
            @foreach(['open','resolved','escalated'] as $s)
                <option value="{{ $s }}" {{ ($filter['status'] ?? '') === $s ? 'selected' : '' }}>
                    {{ ucfirst($s) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <input type="number" name="worker_id" class="form-control" placeholder="Worker ID"
               value="{{ $filter['worker_id'] ?? '' }}">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
    </div>
    <div class="col-md-2">
        <a href="{{ route('titango.admin.issues.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Visit ID</th>
            <th>Worker ID</th>
            <th>Type</th>
            <th>Description</th>
            <th>Status</th>
            <th>Reported</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($issues as $issue)
            <tr>
                <td>{{ $issue->id }}</td>
                <td>
                    @if(class_exists(\Modules\FSMCore\Models\FSMOrder::class))
                        <a href="{{ route('fsmcore.orders.show', $issue->visit_id) }}">
                            #{{ $issue->visit_id }}
                        </a>
                    @else
                        #{{ $issue->visit_id }}
                    @endif
                </td>
                <td>{{ $issue->worker_id }}</td>
                <td>
                    <span class="badge
                        @if(str_contains($issue->type,'safety') || str_contains($issue->type,'damage')) bg-danger
                        @elseif(str_contains($issue->type,'access') || str_contains($issue->type,'absent')) bg-warning text-dark
                        @elseif($issue->type === 'extra_work') bg-info text-dark
                        @else bg-secondary
                        @endif">
                        {{ str_replace('_', ' ', ucfirst($issue->type)) }}
                    </span>
                </td>
                <td class="text-truncate" style="max-width:200px">{{ $issue->description ?? '—' }}</td>
                <td>
                    <span class="badge
                        @if($issue->status === 'open') bg-warning text-dark
                        @elseif($issue->status === 'resolved') bg-success
                        @else bg-danger
                        @endif">
                        {{ ucfirst($issue->status) }}
                    </span>
                </td>
                <td>{{ $issue->created_at?->diffForHumans() }}</td>
                <td>
                    <form method="POST" action="{{ route('titango.admin.issues.update', $issue->id) }}"
                          class="d-flex gap-1 align-items-center">
                        @csrf
                        <select name="status" class="form-select form-select-sm w-auto">
                            @foreach(['open','resolved','escalated'] as $s)
                                <option value="{{ $s }}" {{ $issue->status === $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-sm btn-outline-primary">Save</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center text-muted">No issues found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

{{ $issues->links() }}
@endsection
