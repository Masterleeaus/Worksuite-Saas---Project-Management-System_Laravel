@extends('fsmroute::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Dispatch Board – {{ $date->format('D, M j Y') }}</h2>
    <a href="{{ route('fsmroute.day_routes.create') }}" class="btn btn-success btn-sm">+ New Day Route</a>
</div>

<form method="GET" class="row g-2 mb-4">
    <div class="col-md-3">
        <input type="date" name="date" class="form-control" value="{{ $date->format('Y-m-d') }}">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary">Go</button>
    </div>
</form>

@if($dayRoutes->isEmpty())
    <div class="alert alert-info">No day routes for this date.</div>
@else
<div class="row g-3">
    @foreach($dayRoutes as $dr)
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>{{ $dr->person?->name ?? 'Unassigned' }}</strong>
                <span class="badge bg-secondary">{{ $dr->orderCount() }} orders</span>
            </div>
            <div class="card-body p-2">
                <ul class="list-group list-group-flush sortable-list" id="sortable-{{ $dr->id }}"
                    data-dayroute-id="{{ $dr->id }}"
                    data-reorder-url="{{ route('fsmroute.day_routes.reorder', $dr->id) }}">
                    @foreach($dr->orders as $order)
                    <li class="list-group-item d-flex justify-content-between align-items-center py-1 px-2"
                        data-order-id="{{ $order->id }}">
                        <div>
                            <span class="badge bg-light text-dark me-1">{{ $order->route_sequence + 1 }}</span>
                            <a href="{{ route('fsmcore.orders.show', $order->id) }}" class="text-decoration-none">
                                {{ $order->name }}
                            </a>
                            @if($order->location)
                                <br><small class="text-muted ms-3">📍 {{ $order->location->name }}</small>
                            @endif
                        </div>
                        <span class="drag-handle text-muted" style="cursor:grab;">⠿</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('fsmroute.day_routes.edit', $dr->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                <a href="{{ route('fsmroute.day_routes.print', $dr->id) }}" class="btn btn-sm btn-outline-secondary" target="_blank">Print</a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
document.querySelectorAll('.sortable-list').forEach(function (el) {
    Sortable.create(el, {
        handle: '.drag-handle',
        animation: 150,
        onEnd: function () {
            var orderIds = Array.from(el.querySelectorAll('[data-order-id]'))
                               .map(function (li) { return li.dataset.orderId; });
            fetch(el.dataset.reorderUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ order_ids: orderIds })
            });
        }
    });
});
</script>
@endsection
