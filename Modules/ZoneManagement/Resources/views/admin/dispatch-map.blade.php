@extends('adminmodule::layouts.new-master')

@section('title', translate('Dispatch Map – Live Cleaner Positions'))

@push('css_or_js')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        #dispatch-map { height: 70vh; width: 100%; border-radius: 8px; }
        .cleaner-popup img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 8px; }
        .legend { background: #fff; padding: 10px 14px; border-radius: 6px; box-shadow: 0 1px 4px rgba(0,0,0,.2); }
    </style>
@endpush

@section('content')
<div class="main-content">
    <div class="container-fluid">

        <div class="page-title-wrap mb-3 d-flex align-items-center justify-content-between">
            <h2 class="page-title">{{ translate('Dispatch Map') }}</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.gps.settings') }}" class="btn btn--secondary btn--sm">
                    <i class="bi bi-gear"></i> {{ translate('GPS Settings') }}
                </a>
                <button id="btn-refresh" class="btn btn--primary btn--sm">
                    <i class="bi bi-arrow-clockwise"></i> {{ translate('Refresh') }}
                </button>
            </div>
        </div>

        {{-- Stats bar --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card text-center p-3">
                    <div class="fs-3 fw-bold text-primary" id="stat-online">{{ $liveLocations->count() }}</div>
                    <div class="text-muted small">{{ translate('Cleaners Online') }}</div>
                </div>
            </div>
        </div>

        {{-- Map --}}
        <div class="card mb-4">
            <div class="card-body p-0 overflow-hidden rounded">
                <div id="dispatch-map"></div>
            </div>
        </div>

        {{-- Nearby suggestion helper --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">{{ translate('Find Nearest Cleaners to a Job Site') }}</h5>
            </div>
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">{{ translate('Job Site Latitude') }}</label>
                        <input type="number" step="any" id="suggest-lat" class="form-control" placeholder="-33.8688">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ translate('Job Site Longitude') }}</label>
                        <input type="number" step="any" id="suggest-lng" class="form-control" placeholder="151.2093">
                    </div>
                    <div class="col-md-4">
                        <button id="btn-suggest" class="btn btn--primary w-100">
                            {{ translate('Find Nearest Cleaners') }}
                        </button>
                    </div>
                </div>
                <div id="suggest-results" class="mt-3"></div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('script')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV/XN/sp0=" crossorigin=""></script>
<script>
(function () {
    'use strict';

    const apiBase = '{{ url("api/v1/gps") }}';
    const csrfToken = '{{ csrf_token() }}';

    // Initial data from server-side render
    const initialLocations = @json($liveLocations);

    // ── Map init ────────────────────────────────────────────────────────────
    const map = L.map('dispatch-map').setView([-33.8688, 151.2093], 11);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19,
    }).addTo(map);

    const markers = {};

    function markerIcon(profileImage) {
        return L.divIcon({
            html: `<div style="width:36px;height:36px;border-radius:50%;border:3px solid #0d6efd;overflow:hidden;background:#e9ecef;">
                       <img src="${profileImage || '/public/assets/placeholder-user.png'}" style="width:100%;height:100%;object-fit:cover;">
                   </div>`,
            className: '',
            iconSize: [36, 36],
            iconAnchor: [18, 18],
        });
    }

    function addOrUpdateMarker(loc) {
        const lat = parseFloat(loc.lat);
        const lng = parseFloat(loc.lng);
        if (isNaN(lat) || isNaN(lng)) return;

        const name = loc.user
            ? (loc.user.first_name + ' ' + loc.user.last_name).trim()
            : 'Cleaner #' + loc.user_id;

        const imgSrc = loc.user && loc.user.profile_image
            ? '/storage/' + loc.user.profile_image
            : '/public/assets/placeholder-user.png';

        const popup = `<div class="cleaner-popup d-flex align-items-center">
            <img src="${imgSrc}" alt="">
            <div>
                <strong>${name}</strong><br>
                <small class="text-muted">Last seen: ${loc.recorded_at ?? 'unknown'}</small>
            </div>
        </div>`;

        if (markers[loc.user_id]) {
            markers[loc.user_id].setLatLng([lat, lng]).setPopupContent(popup);
        } else {
            markers[loc.user_id] = L.marker([lat, lng], { icon: markerIcon(imgSrc) })
                .addTo(map)
                .bindPopup(popup);
        }
    }

    initialLocations.forEach(addOrUpdateMarker);

    // ── Refresh ─────────────────────────────────────────────────────────────
    async function refreshLocations() {
        try {
            const res = await fetch(`${apiBase}/live-locations`, {
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            });
            if (!res.ok) return;
            const data = await res.json();
            data.forEach(addOrUpdateMarker);
            document.getElementById('stat-online').textContent = data.length;
        } catch (e) {
            console.error('Dispatch map refresh failed:', e);
        }
    }

    document.getElementById('btn-refresh').addEventListener('click', refreshLocations);

    // Auto-refresh every 60 seconds
    setInterval(refreshLocations, 60000);

    // ── Nearby suggestion ───────────────────────────────────────────────────
    document.getElementById('btn-suggest').addEventListener('click', async () => {
        const lat = document.getElementById('suggest-lat').value;
        const lng = document.getElementById('suggest-lng').value;
        const container = document.getElementById('suggest-results');

        if (!lat || !lng) {
            container.innerHTML = '<div class="alert alert-warning">{{ translate("Please enter both lat and lng.") }}</div>';
            return;
        }

        container.innerHTML = '<div class="text-muted">{{ translate("Loading...") }}</div>';

        try {
            const res = await fetch(
                `${apiBase.replace('/gps', '/admin/gps')}/nearby-cleaners?lat=${encodeURIComponent(lat)}&lng=${encodeURIComponent(lng)}&limit=5`,
                { headers: { 'Accept': 'application/json' } }
            );
            const data = await res.json();

            if (!data.length) {
                container.innerHTML = '<div class="alert alert-info">{{ translate("No cleaners with recent location data.") }}</div>';
                return;
            }

            let html = '<ul class="list-group">';
            data.forEach((c, i) => {
                html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><strong>#${i + 1}</strong> ${c.name}</span>
                    <span class="badge bg-primary rounded-pill">${c.distance_km} km away</span>
                </li>`;
            });
            html += '</ul>';
            container.innerHTML = html;

            // Place a target marker on the map
            L.marker([parseFloat(lat), parseFloat(lng)], {
                icon: L.divIcon({ html: '<div style="background:#dc3545;width:16px;height:16px;border-radius:50%;border:2px solid #fff;"></div>', className: '', iconSize: [16, 16], iconAnchor: [8, 8] })
            }).addTo(map).bindPopup('{{ translate("Target job site") }}').openPopup();

        } catch (e) {
            container.innerHTML = '<div class="alert alert-danger">{{ translate("Failed to load suggestions.") }}</div>';
        }
    });
})();
</script>
@endpush
