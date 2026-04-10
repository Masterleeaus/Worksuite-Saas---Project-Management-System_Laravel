@extends('adminmodule::layouts.new-master')

@section('title', translate('GPS System Settings'))

@section('content')
<div class="main-content">
    <div class="container-fluid">

        <div class="page-title-wrap mb-3">
            <h2 class="page-title">{{ translate('GPS & Location Settings') }}</h2>
        </div>

        <div class="row">
            <div class="col-lg-8 col-xl-7">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.gps.settings.update') }}" method="POST">
                            @csrf

                            {{-- Live Tracking --}}
                            <h5 class="mb-3 border-bottom pb-2">{{ translate('Live Tracking') }}</h5>

                            <div class="form-group mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="live_tracking_enabled"
                                           id="live_tracking_enabled" value="1"
                                           {{ $settings->live_tracking_enabled ? 'checked' : '' }}>
                                    <label class="form-check-label" for="live_tracking_enabled">
                                        {{ translate('Enable Live Tracking') }}
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-1">
                                    {{ translate('When enabled, the PWA will send periodic GPS pings while a cleaner is checked in.') }}
                                </small>
                            </div>

                            <div class="form-floating form-floating__icon mb-30">
                                <input type="number" class="form-control" id="location_ping_interval"
                                       name="location_ping_interval" min="10" max="600"
                                       value="{{ old('location_ping_interval', $settings->location_ping_interval ?? 60) }}">
                                <label for="location_ping_interval">{{ translate('Location Ping Interval (seconds)') }}</label>
                                <span class="material-icons">timer</span>
                                <small class="text-muted d-block mt-1">
                                    {{ translate('How often the PWA sends a location update (10–600 seconds). Default: 60.') }}
                                </small>
                            </div>

                            {{-- Geofence Defaults --}}
                            <h5 class="mb-3 border-bottom pb-2 mt-4">{{ translate('Geofence Defaults') }}</h5>

                            <div class="form-floating form-floating__icon mb-30">
                                <input type="number" class="form-control" id="default_geofence_radius"
                                       name="default_geofence_radius" min="10" max="50000"
                                       value="{{ old('default_geofence_radius', $settings->default_geofence_radius ?? 200) }}">
                                <label for="default_geofence_radius">{{ translate('Default Geofence Radius (metres)') }}</label>
                                <span class="material-icons">radar</span>
                                <small class="text-muted d-block mt-1">
                                    {{ translate('Default radius applied to new job site zones. Default: 200 m.') }}
                                </small>
                            </div>

                            <div class="form-floating form-floating__icon mb-30">
                                <input type="number" class="form-control" id="poor_accuracy_threshold"
                                       name="poor_accuracy_threshold" min="5" max="500"
                                       value="{{ old('poor_accuracy_threshold', $settings->poor_accuracy_threshold ?? 50) }}">
                                <label for="poor_accuracy_threshold">{{ translate('Poor Accuracy Threshold (metres)') }}</label>
                                <span class="material-icons">gps_not_fixed</span>
                                <small class="text-muted d-block mt-1">
                                    {{ translate('If GPS accuracy is worse than this value, check-in is flagged as unverified. Default: 50 m.') }}
                                </small>
                            </div>

                            {{-- Route Recording --}}
                            <h5 class="mb-3 border-bottom pb-2 mt-4">{{ translate('Route Recording') }}</h5>

                            <div class="form-group mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="route_recording_enabled"
                                           id="route_recording_enabled" value="1"
                                           {{ $settings->route_recording_enabled ? 'checked' : '' }}>
                                    <label class="form-check-label" for="route_recording_enabled">
                                        {{ translate('Enable Route Recording (opt-in)') }}
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-1">
                                    {{ translate('Allows cleaners to opt in to GPS path recording between jobs.') }}
                                </small>
                            </div>

                            {{-- Data Retention (Privacy) --}}
                            <h5 class="mb-3 border-bottom pb-2 mt-4">{{ translate('Data Retention & Privacy') }}</h5>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-1"></i>
                                {{ translate('Configuring shorter retention periods helps comply with the Australian Privacy Act (APP 11 — Security of Personal Information).') }}
                            </div>

                            <div class="form-floating form-floating__icon mb-30">
                                <input type="number" class="form-control" id="route_data_retention_days"
                                       name="route_data_retention_days" min="1" max="3650"
                                       value="{{ old('route_data_retention_days', $settings->route_data_retention_days ?? 90) }}">
                                <label for="route_data_retention_days">{{ translate('Route Data Retention (days)') }}</label>
                                <span class="material-icons">route</span>
                                <small class="text-muted d-block mt-1">
                                    {{ translate('Route point records older than this are automatically purged. Default: 90 days.') }}
                                </small>
                            </div>

                            <div class="form-floating form-floating__icon mb-30">
                                <input type="number" class="form-control" id="location_data_retention_days"
                                       name="location_data_retention_days" min="1" max="3650"
                                       value="{{ old('location_data_retention_days', $settings->location_data_retention_days ?? 30) }}">
                                <label for="location_data_retention_days">{{ translate('Live Location Data Retention (days)') }}</label>
                                <span class="material-icons">location_on</span>
                                <small class="text-muted d-block mt-1">
                                    {{ translate('Cleaner live-ping records older than this are automatically purged. Default: 30 days.') }}
                                </small>
                            </div>

                            {{-- Map Provider --}}
                            <h5 class="mb-3 border-bottom pb-2 mt-4">{{ translate('Map Provider') }}</h5>

                            <div class="form-floating mb-30">
                                <select class="form-select" id="map_provider" name="map_provider">
                                    <option value="openstreetmap" {{ ($settings->map_provider ?? 'openstreetmap') === 'openstreetmap' ? 'selected' : '' }}>
                                        OpenStreetMap (free)
                                    </option>
                                    <option value="google" {{ ($settings->map_provider ?? '') === 'google' ? 'selected' : '' }}>
                                        Google Maps
                                    </option>
                                </select>
                                <label for="map_provider">{{ translate('Map Provider') }}</label>
                            </div>

                            <div id="google-key-group" class="form-floating form-floating__icon mb-30"
                                 style="{{ ($settings->map_provider ?? 'openstreetmap') !== 'google' ? 'display:none' : '' }}">
                                <input type="text" class="form-control" id="google_maps_key"
                                       name="google_maps_key"
                                       value="{{ old('google_maps_key', $settings->google_maps_key ?? '') }}">
                                <label for="google_maps_key">{{ translate('Google Maps API Key') }}</label>
                                <span class="material-icons">key</span>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn--primary px-5">
                                    {{ translate('Save Settings') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('script')
<script>
document.getElementById('map_provider').addEventListener('change', function () {
    document.getElementById('google-key-group').style.display =
        this.value === 'google' ? '' : 'none';
});
</script>
@endpush
