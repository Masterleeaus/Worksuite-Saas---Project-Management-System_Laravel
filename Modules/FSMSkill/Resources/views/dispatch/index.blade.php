@extends('fsmskill::layouts.master')

@section('fsmskill_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Dispatch – Skill-Based Worker Matching</h2>
</div>

{{-- Order selector --}}
<form method="GET" class="row g-2 mb-4">
    <div class="col-md-4">
        <label class="form-label">Select FSM Order to filter by skills:</label>
        <select name="order_id" class="form-select" onchange="this.form.submit()">
            <option value="">— All workers —</option>
            @foreach(\Modules\FSMCore\Models\FSMOrder::orderByDesc('id')->get() as $o)
                <option value="{{ $o->id }}" {{ ($order?->id == $o->id) ? 'selected' : '' }}>
                    {{ $o->name }}
                </option>
            @endforeach
        </select>
    </div>
</form>

@if($order)
    <div class="alert alert-info">
        Showing workers qualified for order <strong>{{ $order->name }}</strong>.
        <a href="{{ route('fsmskill.order-skills.index', $order->id) }}">Manage skill requirements →</a>
    </div>
@endif

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>Worker</th>
            <th>Email</th>
            @if($order)
                <th>Skill Match</th>
                <th>Issues / Warnings</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @php $list = $order ? $workers->filter(fn($w) => array_key_exists($w->id, $matchResults)) : $workers; @endphp
        @forelse($list as $worker)
            @php $result = $matchResults[$worker->id] ?? null; @endphp
            <tr>
                <td>
                    <a href="{{ route('fsmskill.employee-skills.index', $worker->id) }}">{{ $worker->name }}</a>
                </td>
                <td>{{ $worker->email }}</td>
                @if($order)
                    <td>
                        @if($result === null)
                            <span class="badge bg-secondary">—</span>
                        @elseif($result['match'])
                            <span class="badge bg-success">✔ Qualified</span>
                        @else
                            <span class="badge bg-danger">✘ Not Qualified</span>
                        @endif
                    </td>
                    <td>
                        @if($result)
                            @foreach($result['issues'] as $issue)
                                <div class="text-danger small">⛔ {{ $issue }}</div>
                            @endforeach
                            @foreach($result['warnings'] as $warn)
                                <div class="text-warning small">⚠ {{ $warn }}</div>
                            @endforeach
                        @endif
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="{{ $order ? 4 : 2 }}" class="text-center text-muted py-4">
                    No workers found.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
