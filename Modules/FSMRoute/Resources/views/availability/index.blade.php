@extends('fsmroute::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Worker Availability</h2>
    <div>
        @php
            $prevWeek = $weekStart->copy()->subWeek()->format('Y-m-d');
            $nextWeek = $weekStart->copy()->addWeek()->format('Y-m-d');
        @endphp
        <a href="{{ route('fsmroute.availability.index', ['date' => $prevWeek]) }}" class="btn btn-outline-secondary btn-sm">‹ Prev</a>
        <span class="mx-2 fw-semibold">{{ $weekStart->format('M j') }} – {{ $weekStart->copy()->endOfWeek()->format('M j, Y') }}</span>
        <a href="{{ route('fsmroute.availability.index', ['date' => $nextWeek]) }}" class="btn btn-outline-secondary btn-sm">Next ›</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-sm align-middle text-center">
        <thead class="table-light">
        <tr>
            <th class="text-start">Worker</th>
            @foreach($weekDays as $day)
                <th>{{ $day->format('D') }}<br><small class="text-muted">{{ $day->format('M j') }}</small></th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td class="text-start fw-semibold">{{ $user->name }}</td>
                @foreach($weekDays as $day)
                    @php
                        $dateStr = $day->format('Y-m-d');
                        $record  = $availability[$user->id][$dateStr] ?? null;
                        $unavail = $record && $record->available; // available=true means unavailable in Odoo semantics
                    @endphp
                    <td>
                        @if($unavail)
                            <span class="text-danger fw-bold" title="{{ $record->reason ?? 'Unavailable' }}">✗</span>
                            @if($record->reason)
                                <br><small class="text-muted">{{ Str::limit($record->reason, 20) }}</small>
                            @endif
                            <form method="POST" action="{{ route('fsmroute.availability.destroy') }}" class="mt-1">
                                @csrf
                                <input type="hidden" name="person_id" value="{{ $user->id }}">
                                <input type="hidden" name="date" value="{{ $dateStr }}">
                                <button class="btn btn-xs btn-outline-success btn-sm py-0 px-1" style="font-size:0.7rem">Mark Available</button>
                            </form>
                        @else
                            <span class="text-success">✓</span>
                            <form method="POST" action="{{ route('fsmroute.availability.store') }}" class="mt-1">
                                @csrf
                                <input type="hidden" name="person_id" value="{{ $user->id }}">
                                <input type="hidden" name="date" value="{{ $dateStr }}">
                                <input type="hidden" name="available" value="1">
                                <button class="btn btn-sm btn-outline-danger py-0 px-1" style="font-size:0.7rem">Mark Off</button>
                            </form>
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

{{-- Quick mark-off form with reason --}}
<div class="card mt-4">
    <div class="card-header">Mark Worker Unavailable</div>
    <div class="card-body">
        <form method="POST" action="{{ route('fsmroute.availability.store') }}" class="row g-3">
            @csrf
            <div class="col-md-3">
                <label class="form-label">Worker</label>
                <select name="person_id" class="form-select" required>
                    <option value="">— Select —</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Reason</label>
                <input type="text" name="reason" class="form-control" maxlength="256" placeholder="Holiday, sick leave…">
            </div>
            <input type="hidden" name="available" value="1">
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-warning w-100">Mark Unavailable</button>
            </div>
        </form>
    </div>
</div>
@endsection
