@extends('fsmcrm::layouts.master')

@section('fsmcrm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>CRM Leads</h2>
    <a href="{{ route('fsmcrm.leads.create') }}" class="btn btn-success">+ New Lead</a>
</div>

{{-- Filters --}}
<form method="GET" class="row g-2 mb-3">
    <div class="col-auto">
        <select name="stage" class="form-select form-select-sm">
            <option value="">All Stages</option>
            @foreach($stages as $key => $label)
                <option value="{{ $key }}" @selected(($filter['stage'] ?? '') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <input type="text" name="q" class="form-control form-control-sm" placeholder="Search…" value="{{ $filter['q'] ?? '' }}">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-outline-secondary btn-sm">Filter</button>
        <a href="{{ route('fsmcrm.leads.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>Title</th>
            <th>Contact</th>
            <th>Stage</th>
            <th>Revenue</th>
            <th>Close Date</th>
            <th>FSM Location</th>
            <th>FSM Orders</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($leads as $lead)
            <tr>
                <td><a href="{{ route('fsmcrm.leads.show', $lead->id) }}">{{ $lead->name }}</a></td>
                <td>{{ $lead->contact_name ?? '—' }}</td>
                <td>
                    @php
                        $stageColors = \Modules\FSMCRM\Models\FSMLead::stageColors();
                        $stageColor = $stageColors[$lead->stage] ?? 'secondary';
                    @endphp
                    <span class="badge bg-{{ $stageColor }}">{{ $stages[$lead->stage] ?? $lead->stage }}</span>
                </td>
                <td>{{ $lead->expected_revenue > 0 ? number_format($lead->expected_revenue, 2) : '—' }}</td>
                <td>{{ $lead->close_date?->format('d M Y') ?? '—' }}</td>
                <td>{{ $lead->fsmLocation?->name ?? '—' }}</td>
                <td>
                    @if($lead->orders_count > 0)
                        <a href="{{ route('fsmcrm.leads.show', $lead->id) }}" class="badge bg-primary text-decoration-none">{{ $lead->orders_count }}</a>
                    @else
                        <span class="text-muted">0</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('fsmcrm.leads.show', $lead->id) }}" class="btn btn-sm btn-outline-secondary">View</a>
                    <a href="{{ route('fsmcrm.leads.edit', $lead->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    @if($lead->stage === 'won')
                        <a href="{{ route('fsmcrm.leads.convert', $lead->id) }}" class="btn btn-sm btn-success">🔧 Create FSM Order</a>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="8" class="text-center text-muted">No leads found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

{{ $leads->withQueryString()->links() }}
@endsection
