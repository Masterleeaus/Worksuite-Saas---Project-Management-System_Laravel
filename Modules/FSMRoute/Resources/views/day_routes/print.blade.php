@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h2>Route Sheet: {{ $dayRoute->name }}</h2>
            <p class="text-muted mb-0">
                <strong>Date:</strong> {{ $dayRoute->date->format('D, M j Y') }} &nbsp;|&nbsp;
                <strong>Worker:</strong> {{ $dayRoute->person?->name ?? 'Unassigned' }} &nbsp;|&nbsp;
                <strong>Route:</strong> {{ $dayRoute->route?->name ?? '—' }}
            </p>
        </div>
        <button class="btn btn-sm btn-outline-secondary d-print-none" onclick="window.print()">🖨 Print</button>
    </div>

    <table class="table table-bordered table-sm">
        <thead class="table-light">
        <tr>
            <th style="width:40px">#</th>
            <th>Order</th>
            <th>Location</th>
            <th>Address</th>
            <th>Notes</th>
        </tr>
        </thead>
        <tbody>
        @forelse($dayRoute->orders as $order)
            <tr>
                <td>{{ $order->route_sequence + 1 }}</td>
                <td>{{ $order->name }}</td>
                <td>{{ $order->location?->name ?? '—' }}</td>
                <td>
                    @if($order->location)
                        {{ $order->location->street ? $order->location->street . ', ' : '' }}
                        {{ $order->location->city }}
                        {{ $order->location->zip ? ' ' . $order->location->zip : '' }}
                    @else
                        —
                    @endif
                </td>
                <td>{{ $order->description ?? '' }}</td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted">No orders.</td></tr>
        @endforelse
        </tbody>
    </table>

    <p class="mt-3 text-muted small">
        Planned start: {{ $dayRoute->date_start_planned?->format('H:i') ?? '—' }} &nbsp;|&nbsp;
        Work time: {{ $dayRoute->work_time }}h &nbsp;|&nbsp;
        Max: {{ $dayRoute->max_allow_time }}h
    </p>
</div>

<script>
    window.addEventListener('load', function () { window.print(); });
</script>
@endsection
