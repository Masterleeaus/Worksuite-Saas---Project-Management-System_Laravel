@extends('layouts.main')

@section('page-title')
    {{ __('bookingmodule::settings.legacy_import') }}
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{{ __('bookingmodule::settings.legacy_import') }}</h5>
    </div>
    <div class="card-body">
        <p class="text-muted mb-3">
            {{ __('bookingmodule::settings.legacy_import_help') }}
        </p>

        @php
            $hasLegacy = \Illuminate\Support\Facades\Schema::hasTable('wo_service_appointments');
        @endphp

        @if(!$hasLegacy)
            <div class="alert alert-info mb-0">
                {{ __('bookingmodule::settings.legacy_import_not_found') }}
            </div>
        @else
            <div class="alert alert-warning">
                {{ __('bookingmodule::settings.legacy_import_warning') }}
            </div>

            <pre class="mb-0"><code>php artisan appointment:import-legacy-wo-appointments --dry-run
php artisan appointment:import-legacy-wo-appointments --limit=200</code></pre>
        @endif
    </div>
</div>
@endsection
