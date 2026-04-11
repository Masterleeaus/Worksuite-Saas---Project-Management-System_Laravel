@extends('synapsedispatch::layouts.master')

@section('synapse_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Dispatch Locations</h2>
    <a href="{{ route('synapsedispatch.locations.create') }}" class="btn btn-success">+ New Location</a>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="q" class="form-control form-control-sm" placeholder="Search code / address…"
               value="{{ $filter['q'] ?? '' }}">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary btn-sm">Search</button>
        <a href="{{ route('synapsedispatch.locations.index') }}" class="btn btn-secondary btn-sm">Reset</a>
    </div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Code</th>
                    <th>Address</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($locations as $location)
                <tr>
                    <td><code>{{ $location->location_code }}</code></td>
                    <td>{{ $location->address ?? '—' }}</td>
                    <td>{{ $location->geo_latitude ?? '—' }}</td>
                    <td>{{ $location->geo_longitude ?? '—' }}</td>
                    <td class="text-end">
                        <a href="{{ route('synapsedispatch.locations.edit', $location) }}" class="btn btn-xs btn-outline-secondary">Edit</a>
                        <form method="POST" action="{{ route('synapsedispatch.locations.destroy', $location) }}" class="d-inline"
                              onsubmit="return confirm('Delete location {{ $location->location_code }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-3">No locations found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($locations->hasPages())
    <div class="card-footer">{{ $locations->links() }}</div>
    @endif
</div>
@endsection
