@extends('fsmequipment::layouts.master')

@section('fsmequipment_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Repair Order Templates</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmequipment.repair-templates.create') }}" class="btn btn-success">+ New Template</a>
        <a href="{{ route('fsmequipment.repair-orders.index') }}" class="btn btn-outline-secondary">Repair Orders</a>
    </div>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="q" class="form-control" placeholder="Search…" value="{{ $filter['q'] ?? '' }}">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary">Filter</button>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Est. Hours</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($templates as $tpl)
            <tr>
                <td>{{ $tpl->name }}</td>
                <td>{{ $tpl->equipment_category ?? '—' }}</td>
                <td>{{ $tpl->estimated_hours !== null ? number_format($tpl->estimated_hours, 1) . 'h' : '—' }}</td>
                <td>
                    <a href="{{ route('fsmequipment.repair-templates.edit', $tpl->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="{{ route('fsmequipment.repair-templates.destroy', $tpl->id) }}" class="d-inline" onsubmit="return confirm('Delete template?')">
                        @csrf <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted py-4">No templates found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $templates->links() }}
@endsection
