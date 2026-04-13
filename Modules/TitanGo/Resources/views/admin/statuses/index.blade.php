@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Titan Go — Worker Status Signals</h2>
</div>

{{-- Filters --}}
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">All Signals</option>
            @foreach($signalTypes as $sig)
                <option value="{{ $sig }}" {{ ($filter['status'] ?? '') === $sig ? 'selected' : '' }}>
                    {{ str_replace('_', ' ', ucfirst($sig)) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <input type="number" name="worker_id" class="form-control" placeholder="Worker ID"
               value="{{ $filter['worker_id'] ?? '' }}">
    </div>
    <div class="col-md-2">
        <input type="number" name="visit_id" class="form-control" placeholder="Visit ID"
               value="{{ $filter['visit_id'] ?? '' }}">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
    </div>
    <div class="col-md-2">
        <a href="{{ route('titango.admin.statuses.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Worker ID</th>
            <th>Visit ID</th>
            <th>Signal</th>
            <th>Notes</th>
            <th>Time</th>
        </tr>
        </thead>
        <tbody>
        @forelse($statuses as $row)
            <tr>
                <td>{{ $row->id }}</td>
                <td>{{ $row->worker_id }}</td>
                <td>
                    @if(class_exists(\Modules\FSMCore\Models\FSMOrder::class))
                        <a href="{{ route('fsmcore.orders.show', $row->visit_id) }}">#{{ $row->visit_id }}</a>
                    @else
                        #{{ $row->visit_id }}
                    @endif
                </td>
                <td>
                    <span class="badge
                        @if($row->status === 'job_complete') bg-success
                        @elseif(in_array($row->status, ['access_issue','report_issue'])) bg-danger
                        @elseif($row->status === 'running_late') bg-warning text-dark
                        @elseif($row->status === 'arrived') bg-primary
                        @else bg-secondary
                        @endif">
                        {{ str_replace('_', ' ', ucfirst($row->status)) }}
                    </span>
                </td>
                <td class="text-truncate" style="max-width:180px">{{ $row->notes ?? '—' }}</td>
                <td>{{ $row->created_at?->diffForHumans() }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted">No status signals found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

{{ $statuses->links() }}
@endsection
