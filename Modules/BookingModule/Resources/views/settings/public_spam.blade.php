@extends('layouts.main')

@section('page-title')
    {{ __('bookingmodule::settings.public_spam_title') }}
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{{ __('bookingmodule::settings.public_spam_title') }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('appointment.settings.public_spam.update') }}">
            @csrf
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="enable_honeypot" name="enable_honeypot" value="1" {{ $enable_honeypot ? 'checked' : '' }}>
                <label class="form-check-label" for="enable_honeypot">
                    {{ __('bookingmodule::settings.enable_honeypot') }}
                </label>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('bookingmodule::settings.honeypot_min_seconds') }}</label>
                <input type="number" class="form-control" name="honeypot_min_seconds" value="{{ $honeypot_min_seconds }}" min="0" max="60">
                <div class="text-muted small">{{ __('bookingmodule::settings.honeypot_help') }}</div>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('bookingmodule::settings.rate_limit_per_minute') }}</label>
                <input type="number" class="form-control" name="rate_limit_per_minute" value="{{ $rate_limit_per_minute }}" min="1" max="600">
                <div class="text-muted small">{{ __('bookingmodule::settings.rate_limit_help') }}</div>
            </div>

            <button type="submit" class="btn btn-primary">
                {{ __('Save') }}
            </button>
        </form>
    </div>
</div>
@endsection
