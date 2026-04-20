@extends('fsmequipmentwarranty::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Warranty Profiles</h2>
    <a href="{{ route('fsmequipmentwarranty.profiles.create') }}" class="btn btn-success">+ New Profile</a>
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
            <th>Duration</th>
            <th>Type</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($profiles as $profile)
            <tr>
                <td>{{ $profile->name }}</td>
                <td>{{ $profile->equipment_category ?? '—' }}</td>
                <td>{{ $profile->warranty_duration }}</td>
                <td>{{ \Modules\FSMEquipmentWarranty\Models\FSMWarrantyProfile::TYPES[$profile->warranty_type] ?? $profile->warranty_type }}</td>
                <td>
                    <a href="{{ route('fsmequipmentwarranty.profiles.edit', $profile->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="{{ route('fsmequipmentwarranty.profiles.destroy', $profile->id) }}" class="d-inline" onsubmit="return confirm('Delete this profile?')">
                        @csrf
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted py-4">No warranty profiles found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $profiles->links() }}
@endsection
