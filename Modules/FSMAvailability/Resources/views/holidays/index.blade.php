@extends('fsmavailability::layouts.master')

@section('fsmavailability_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Australian Public Holiday Import</h2>
</div>

<div class="card mb-4">
    <div class="card-header fw-semibold">Fetch Holidays</div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Year</label>
                <input type="number" name="year" class="form-control" value="{{ $year }}" min="2000" max="2100">
            </div>
            <div class="col-md-3">
                <label class="form-label">State / Territory</label>
                <select name="state" class="form-select">
                    @foreach($states as $code => $label)
                        <option value="{{ $code }}" {{ $state === $code ? 'selected' : '' }}>
                            {{ $code }} – {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto d-flex align-items-end">
                <button type="submit" class="btn btn-outline-primary">Fetch</button>
            </div>
        </form>
    </div>
</div>

@if(!empty($holidays))
<form method="POST" action="{{ route('fsmavailability.holidays.import') }}">
    @csrf
    <input type="hidden" name="year" value="{{ $year }}">
    <input type="hidden" name="state" value="{{ $state }}">

    <div class="card">
        <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
            <span>{{ count($holidays) }} Holiday(s) for {{ $state }} {{ $year }}</span>
            <button type="submit" class="btn btn-success btn-sm">
                Import Selected for All Workers
            </button>
        </div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead class="table-light">
                <tr>
                    <th><input type="checkbox" id="selectAll" onclick="document.querySelectorAll('.hol-cb').forEach(c=>c.checked=this.checked)"></th>
                    <th>Date</th>
                    <th>Name (Local)</th>
                    <th>National?</th>
                </tr>
                </thead>
                <tbody>
                @foreach($holidays as $h)
                    <tr>
                        <td>
                            <input type="checkbox" name="dates[]" value="{{ $h['date'] }}"
                                   class="hol-cb" checked>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($h['date'])->format('d M Y') }}</td>
                        <td>{{ $h['localName'] ?? $h['name'] ?? '—' }}</td>
                        <td>
                            @if(empty($h['counties']))
                                <span class="badge bg-info text-dark">National</span>
                            @else
                                <span class="badge bg-secondary">State</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</form>
@elseif(request()->filled('year') || request()->filled('state'))
    <div class="alert alert-warning">
        No holidays found. The holiday API may be temporarily unavailable, or no holidays exist for the selected combination.
    </div>
@endif
@endsection
