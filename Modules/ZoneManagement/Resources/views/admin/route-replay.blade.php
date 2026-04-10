@extends('adminmodule::layouts.new-master')

@section('title', translate('Route Replay'))

@push('css_or_js')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        #route-map { height: 65vh; width: 100%; border-radius: 8px; }
        .timeline-dot { width: 12px; height: 12px; border-radius: 50%; display: inline-block; margin-right: 6px; }
        .dot-checkin  { background: #198754; }
        .dot-checkout { background: #dc3545; }
        .dot-route    { background: #0d6efd; }
    </style>
@endpush

@section('content')
<div class="main-content">
    <div class="container-fluid">

        <div class="page-title-wrap mb-3 d-flex align-items-center justify-content-between">
            <h2 class="page-title">{{ translate('Route Replay') }} — <span class="text-muted fs-6">{{ $bookingId }}</span></h2>
            <a href="{{ url()->previous() }}" class="btn btn--secondary btn--sm">
                <i class="bi bi-arrow-left"></i> {{ translate('Back') }}
            </a>
        </div>

        {{-- Stats row --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card text-center p-3">
                    <div class="fs-3 fw-bold text-primary">{{ $routePoints->count() }}</div>
                    <div class="text-muted small">{{ translate('Route Points') }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-center p-3">
                    <div class="fs-3 fw-bold text-success">{{ $checkIns->count() }}</div>
                    <div class="text-muted small">{{ translate('Check-in Events') }}</div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body p-0 overflow-hidden rounded">
                <div id="route-map"></div>
            </div>
        </div>

        {{-- Playback controls --}}
        <div class="card mb-4">
            <div class="card-body d-flex gap-3 align-items-center flex-wrap">
                <button id="btn-play"  class="btn btn--primary">▶ {{ translate('Play') }}</button>
                <button id="btn-pause" class="btn btn--secondary" disabled>⏸ {{ translate('Pause') }}</button>
                <button id="btn-reset" class="btn btn--danger">↺ {{ translate('Reset') }}</button>
                <label class="ms-3 mb-0">{{ translate('Speed') }}:
                    <select id="replay-speed" class="form-select form-select-sm d-inline-block w-auto">
                        <option value="300">1×</option>
                        <option value="150">2×</option>
                        <option value="75">4×</option>
                    </select>
                </label>
                <span class="ms-auto text-muted" id="replay-status"></span>
            </div>
        </div>

        {{-- Check-in audit log --}}
        @if($checkIns->isNotEmpty())
        <div class="card">
            <div class="card-header"><h5 class="mb-0">{{ translate('Check-in / Check-out Audit Log') }}</h5></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr>
                            <th>{{ translate('Checked In') }}</th>
                            <th>{{ translate('Checked Out') }}</th>
                            <th>{{ translate('Accuracy (m)') }}</th>
                            <th>{{ translate('Verified') }}</th>
                            <th>{{ translate('Within Geofence') }}</th>
                        </tr></thead>
                        <tbody>
                        @foreach($checkIns as $ci)
                        <tr>
                            <td>{{ $ci->checked_in_at ?? '—' }}</td>
                            <td>{{ $ci->checked_out_at ?? '—' }}</td>
                            <td>{{ $ci->check_in_accuracy ?? '—' }}</td>
                            <td>
                                @if($ci->is_verified)
                                    <span class="badge bg-success">{{ translate('Yes') }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ translate('Unverified') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($ci->within_geofence)
                                    <span class="badge bg-success">{{ translate('Yes') }}</span>
                                @else
                                    <span class="badge bg-danger">{{ translate('No') }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@push('script')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV/XN/sp0=" crossorigin=""></script>
<script>
(function () {
    'use strict';

    const points   = @json($routePoints);
    const checkIns = @json($checkIns);

    const map = L.map('route-map');
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19,
    }).addTo(map);

    if (!points.length) {
        document.getElementById('route-map').innerHTML =
            '<div class="d-flex align-items-center justify-content-center h-100 text-muted">{{ translate("No route data for this booking.") }}</div>';
        return;
    }

    // Draw full polyline
    const latlngs = points.map(p => [parseFloat(p.lat), parseFloat(p.lng)]);
    const polyline = L.polyline(latlngs, { color: '#0d6efd', weight: 3, opacity: 0.6 }).addTo(map);
    map.fitBounds(polyline.getBounds(), { padding: [40, 40] });

    // Start marker
    L.circleMarker(latlngs[0], { radius: 8, color: '#198754', fillColor: '#198754', fillOpacity: 1 })
        .addTo(map).bindPopup('{{ translate("Start") }}');

    // End marker
    L.circleMarker(latlngs[latlngs.length - 1], { radius: 8, color: '#dc3545', fillColor: '#dc3545', fillOpacity: 1 })
        .addTo(map).bindPopup('{{ translate("End") }}');

    // Check-in markers
    checkIns.forEach(ci => {
        if (ci.check_in_lat && ci.check_in_lng) {
            L.marker([parseFloat(ci.check_in_lat), parseFloat(ci.check_in_lng)], {
                icon: L.divIcon({ html: '<div style="background:#198754;width:14px;height:14px;border-radius:50%;border:2px solid #fff;"></div>', className:'', iconSize:[14,14], iconAnchor:[7,7] })
            }).addTo(map).bindPopup(`<strong>{{ translate("Check-in") }}</strong><br>${ci.checked_in_at ?? ''}`);
        }
        if (ci.check_out_lat && ci.check_out_lng) {
            L.marker([parseFloat(ci.check_out_lat), parseFloat(ci.check_out_lng)], {
                icon: L.divIcon({ html: '<div style="background:#dc3545;width:14px;height:14px;border-radius:50%;border:2px solid #fff;"></div>', className:'', iconSize:[14,14], iconAnchor:[7,7] })
            }).addTo(map).bindPopup(`<strong>{{ translate("Check-out") }}</strong><br>${ci.checked_out_at ?? ''}`);
        }
    });

    // ── Playback ─────────────────────────────────────────────────────────────
    let playIdx    = 0;
    let playTimer  = null;
    const cursor   = L.circleMarker(latlngs[0], { radius: 8, color: '#fd7e14', fillColor: '#fd7e14', fillOpacity: 1 }).addTo(map);
    const statusEl = document.getElementById('replay-status');

    function step() {
        if (playIdx >= latlngs.length) {
            clearInterval(playTimer);
            playTimer = null;
            document.getElementById('btn-play').disabled  = false;
            document.getElementById('btn-pause').disabled = true;
            statusEl.textContent = '{{ translate("Replay complete") }}';
            return;
        }
        cursor.setLatLng(latlngs[playIdx]);
        statusEl.textContent = `${playIdx + 1} / ${latlngs.length}`;
        playIdx++;
    }

    document.getElementById('btn-play').addEventListener('click', () => {
        if (playTimer) return;
        const speed = parseInt(document.getElementById('replay-speed').value, 10);
        playTimer = setInterval(step, speed);
        document.getElementById('btn-play').disabled  = true;
        document.getElementById('btn-pause').disabled = false;
    });

    document.getElementById('btn-pause').addEventListener('click', () => {
        clearInterval(playTimer);
        playTimer = null;
        document.getElementById('btn-play').disabled  = false;
        document.getElementById('btn-pause').disabled = true;
    });

    document.getElementById('btn-reset').addEventListener('click', () => {
        clearInterval(playTimer);
        playTimer = null;
        playIdx   = 0;
        cursor.setLatLng(latlngs[0]);
        document.getElementById('btn-play').disabled  = false;
        document.getElementById('btn-pause').disabled = true;
        statusEl.textContent = '';
    });
})();
</script>
@endpush
