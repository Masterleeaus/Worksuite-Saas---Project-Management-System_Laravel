@extends('fsmsalerecurringagreement::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Recurring — Agreement Links</h2>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <select name="agreement_id" class="form-select">
            <option value="">— All Agreements —</option>
            @foreach($agreements as $agreement)
                <option value="{{ $agreement->id }}" @selected(($filter['agreement_id'] ?? '') == $agreement->id)>
                    {{ $agreement->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('fsmsalerecurringagreement.index') }}" class="btn btn-outline-secondary">Clear</a>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>Recurring Name</th>
            <th>Location</th>
            <th>State</th>
            <th>Agreement</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($recurrings as $recurring)
            <tr>
                <td>{{ $recurring->name }}</td>
                <td>{{ $recurring->location?->name ?? '—' }}</td>
                <td><span class="badge bg-secondary">{{ $recurring->state }}</span></td>
                <td>{{ $recurring->agreement_id ? '#'.$recurring->agreement_id : '—' }}</td>
                <td>
                    <a href="{{ route('fsmsalerecurringagreement.link_form', $recurring->id) }}" class="btn btn-sm btn-outline-primary">Link</a>
                    @if($recurring->agreement_id)
                        <form method="POST" action="{{ route('fsmsalerecurringagreement.unlink', $recurring->id) }}" class="d-inline" onsubmit="return confirm('Unlink agreement?')">
                            @csrf
                            <button class="btn btn-sm btn-outline-warning">Unlink</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted py-4">No recurring schedules found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $recurrings->links() }}
@endsection
