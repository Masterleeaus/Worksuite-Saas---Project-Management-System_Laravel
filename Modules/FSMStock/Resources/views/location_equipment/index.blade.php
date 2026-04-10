@extends('fsmstock::layouts.master')
@section('fsmstock_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Equipment Register — {{ $location->name }}</h4>
</div>

{{-- Add equipment form --}}
<div class="card mb-4" style="max-width:640px">
    <div class="card-header">Add Equipment to Register</div>
    <div class="card-body">
        <form method="POST" action="{{ route('fsmstock.location-equipment.store', $location->id) }}">
            @csrf
            <div class="row g-2 align-items-end">
                <div class="col-md-7">
                    <label class="form-label fw-semibold">Equipment</label>
                    <select name="fsm_equipment_id" class="form-select" required>
                        <option value="">— Select —</option>
                        @foreach($equipment as $eq)
                            <option value="{{ $eq->id }}">{{ $eq->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Notes</label>
                    <input type="text" name="notes" class="form-control">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Add</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Equipment</th><th>Active</th><th>Last Check Event</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registers as $register)
                <tr>
                    <td>{{ optional($register->equipment)->name ?? '-' }}</td>
                    <td>
                        <span class="badge bg-{{ $register->active ? 'success' : 'secondary' }}">
                            {{ $register->active ? 'Yes' : 'No' }}
                        </span>
                    </td>
                    <td>
                        @php $lastEvent = $register->checkEvents->sortByDesc('checked_at')->first(); @endphp
                        @if($lastEvent)
                            <span class="badge bg-{{ $lastEvent->event_type === 'check_in' ? 'success' : 'warning' }}">
                                {{ str_replace('_', ' ', $lastEvent->event_type) }}
                            </span>
                            {{ $lastEvent->checked_at ? $lastEvent->checked_at->format('d M Y H:i') : '' }}
                        @else
                            <span class="text-muted">None</span>
                        @endif
                    </td>
                    <td>
                        {{-- Check In --}}
                        <form method="POST" action="{{ route('fsmstock.equipment-check.store', $register->id) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="event_type" value="check_in">
                            <button type="submit" class="btn btn-sm btn-outline-success">Check In</button>
                        </form>
                        {{-- Check Out --}}
                        <form method="POST" action="{{ route('fsmstock.equipment-check.store', $register->id) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="event_type" value="check_out">
                            <button type="submit" class="btn btn-sm btn-outline-warning">Check Out</button>
                        </form>
                        {{-- Remove --}}
                        <form method="POST" action="{{ route('fsmstock.location-equipment.destroy', $register->id) }}" class="d-inline"
                              onsubmit="return confirm('Remove from register?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted">No equipment registered.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
