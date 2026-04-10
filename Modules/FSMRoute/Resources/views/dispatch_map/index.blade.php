@extends('fsmroute::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Live Dispatch Map – {{ $date->format('D, M j Y') }}</h2>
    <div>
        <a href="{{ route('fsmroute.day_routes.board', ['date' => $date->format('Y-m-d')]) }}"
           class="btn btn-outline-info btn-sm me-2">📋 Board</a>
    </div>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <input type="date" name="date" class="form-control" value="{{ $date->format('Y-m-d') }}">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary">Go</button>
    </div>
</form>

{{-- Legend --}}
<div class="d-flex gap-3 mb-2 small">
    <span><span style="display:inline-block;width:12px;height:12px;background:#0d6efd;border-radius:50%"></span> Worker</span>
    <span><span style="display:inline-block;width:12px;height:12px;background:#198754;border-radius:50%"></span> Job (complete)</span>
    <span><span style="display:inline-block;width:12px;height:12px;background:#ffc107;border-radius:50%"></span> Job (checked in)</span>
    <span><span style="display:inline-block;width:12px;height:12px;background:#6c757d;border-radius:50%"></span> Job (pending)</span>
</div>

{{-- Map container --}}
<div id="dispatch-map" style="height:520px;border-radius:8px;border:1px solid #dee2e6"></div>

{{-- Day routes summary --}}
@if($dayRoutes->isNotEmpty())
<div class="row g-3 mt-3">
    @foreach($dayRoutes as $dr)
    <div class="col-md-4">
        <div class="card">
            <div class="card-header fw-semibold">
                {{ $dr->person?->name ?? 'Unassigned' }}
                <span class="badge bg-secondary ms-1">{{ $dr->orderCount() }} orders</span>
            </div>
            <ul class="list-group list-group-flush">
                @foreach($dr->orders as $order)
                <li class="list-group-item py-1 px-2 d-flex justify-content-between align-items-center small">
                    <div>
                        <span class="text-muted me-1">#{{ $order->route_sequence + 1 }}</span>
                        <a href="{{ route('fsmcore.orders.show', $order->id) }}" class="text-decoration-none">{{ $order->name }}</a>
                        @if($order->location)
                            <br><span class="text-muted ms-2">📍 {{ $order->location->name }}</span>
                        @endif
                    </div>
                    <div class="d-flex gap-1">
                        @if(!$order->date_start)
                            <form method="POST" action="{{ route('fsmroute.orders.enRoute', $order->id) }}">
                                @csrf
                                <button class="btn btn-xs btn-outline-primary py-0 px-1" style="font-size:0.7rem" title="En Route">🚗</button>
                            </form>
                            <form method="POST" action="{{ route('fsmroute.orders.checkIn', $order->id) }}">
                                @csrf
                                <button class="btn btn-xs btn-outline-warning py-0 px-1" style="font-size:0.7rem" title="Check In">✓ In</button>
                            </form>
                        @elseif(!$order->date_end)
                            <span class="badge bg-warning text-dark">In</span>
                            <form method="POST" action="{{ route('fsmroute.orders.checkOut', $order->id) }}">
                                @csrf
                                <button class="btn btn-xs btn-outline-success py-0 px-1" style="font-size:0.7rem" title="Complete">✓ Done</button>
                            </form>
                        @else
                            <span class="badge bg-success">Done</span>
                        @endif
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endforeach
</div>
@else
    <div class="alert alert-info mt-3">No day routes for this date.</div>
@endif

{{-- Leaflet CSS/JS (CDN, no API key required) --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV/XN/WLvI=" crossorigin=""></script>

<script>
(function () {
    var map = L.map('dispatch-map').setView([0, 0], 2);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19
    }).addTo(map);

    var workerLayer = L.layerGroup().addTo(map);
    var jobLayer    = L.layerGroup().addTo(map);
    var bounds      = [];

    var locationsUrl = "{{ route('fsmroute.dispatch_map.locations', ['date' => $date->format('Y-m-d')]) }}";

    function workerIcon() {
        return L.divIcon({
            className: '',
            html: '<div style="width:16px;height:16px;background:#0d6efd;border:2px solid #fff;border-radius:50%;box-shadow:0 1px 3px rgba(0,0,0,.4)"></div>',
            iconSize: [16, 16],
            iconAnchor: [8, 8]
        });
    }

    function jobIcon(color) {
        return L.divIcon({
            className: '',
            html: '<div style="width:14px;height:14px;background:' + color + ';border:2px solid #fff;border-radius:50%;box-shadow:0 1px 3px rgba(0,0,0,.4)"></div>',
            iconSize: [14, 14],
            iconAnchor: [7, 7]
        });
    }

    function loadLocations() {
        fetch(locationsUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            workerLayer.clearLayers();
            bounds = [];

            (data.workers || []).forEach(function (w) {
                var latlng = [w.latitude, w.longitude];
                bounds.push(latlng);
                L.marker(latlng, { icon: workerIcon() })
                    .bindPopup('<strong>👷 ' + w.name + '</strong><br><small>' + w.pinged_at + '</small>')
                    .addTo(workerLayer);
            });

            jobLayer.clearLayers();
            (data.jobs || []).forEach(function (j) {
                var latlng   = [j.latitude, j.longitude];
                var color    = j.complete ? '#198754' : (j.checked_in ? '#ffc107' : '#6c757d');
                var status   = j.complete ? 'Complete' : (j.checked_in ? 'Checked In' : 'Pending');
                bounds.push(latlng);
                L.marker(latlng, { icon: jobIcon(color) })
                    .bindPopup(
                        '<strong>#' + j.sequence + ' ' + j.order_name + '</strong><br>' +
                        '📍 ' + j.location + '<br>' +
                        '👷 ' + j.worker + '<br>' +
                        '<span style="color:' + color + '">' + status + '</span>'
                    )
                    .addTo(jobLayer);
            });

            if (bounds.length) {
                map.fitBounds(bounds, { padding: [40, 40] });
            }
        })
        .catch(function (e) { console.error('Map location fetch failed', e); });
    }

    loadLocations();

    // Auto-refresh every 30 seconds
    setInterval(loadLocations, 30000);
}());
</script>
@endsection
