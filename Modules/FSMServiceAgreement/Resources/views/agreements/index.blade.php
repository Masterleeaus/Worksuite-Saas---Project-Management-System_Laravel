@extends('fsmserviceagreement::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Service Agreements</h2>
    <a href="{{ route('fsmserviceagreement.agreements.create') }}" class="btn btn-success">+ New Agreement</a>
</div>

@if($expiringSoon > 0)
    <div class="alert alert-warning d-flex align-items-center" role="alert">
        <svg class="bi flex-shrink-0 me-2" width="20" height="20" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
        <div>
            <strong>{{ $expiringSoon }} active agreement(s)</strong> expire within the next 30 days.
            <a href="{{ route('fsmserviceagreement.agreements.index', ['state' => 'active']) }}" class="alert-link">View active agreements</a>
        </div>
    </div>
@endif

{{-- Filters --}}
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="q" class="form-control" placeholder="Search by reference…" value="{{ $filter['q'] ?? '' }}">
    </div>
    <div class="col-md-3">
        <select name="state" class="form-select">
            <option value="">All States</option>
            <option value="draft"     {{ ($filter['state'] ?? '') === 'draft'     ? 'selected' : '' }}>Draft</option>
            <option value="active"    {{ ($filter['state'] ?? '') === 'active'    ? 'selected' : '' }}>Active</option>
            <option value="expired"   {{ ($filter['state'] ?? '') === 'expired'   ? 'selected' : '' }}>Expired</option>
            <option value="cancelled" {{ ($filter['state'] ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
    </div>
    <div class="col-md-2">
        <a href="{{ route('fsmserviceagreement.agreements.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>Reference</th>
            <th>Client</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Value</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($agreements as $agreement)
            @php $sl = $agreement->stateLabel(); @endphp
            <tr>
                <td><a href="{{ route('fsmserviceagreement.agreements.show', $agreement->id) }}">{{ $agreement->name }}</a></td>
                <td>{{ $agreement->client?->name ?? '—' }}</td>
                <td>{{ $agreement->start_date?->format('d M Y') ?? '—' }}</td>
                <td>
                    {{ $agreement->end_date?->format('d M Y') ?? 'Ongoing' }}
                    @if($agreement->isExpiringSoon())
                        <span class="badge bg-warning text-dark ms-1">Expiring Soon</span>
                    @endif
                </td>
                <td>${{ number_format($agreement->value, 2) }}</td>
                <td><span class="badge {{ $sl['class'] }}">{{ $sl['label'] }}</span></td>
                <td>
                    <a href="{{ route('fsmserviceagreement.agreements.edit', $agreement->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="{{ route('fsmserviceagreement.agreements.destroy', $agreement->id) }}" class="d-inline"
                          onsubmit="return confirm('Delete this agreement?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center text-muted py-4">No agreements found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

{{ $agreements->links() }}
@endsection
