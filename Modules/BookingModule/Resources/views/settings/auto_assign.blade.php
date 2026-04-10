@extends('layouts.main')

@section('page-title')
    {{ __('bookingmodule::settings.auto_assign_title') }}
@endsection

@section('page-breadcrumb')
    {{ __('Settings') }}
@endsection

@section('content')
<div class="row mb-3"><div class="col-12 d-flex justify-content-end"><a href="{{ route('appointment.settings.staff_capacity') }}" class="btn btn-sm btn-primary">{{ __('bookingmodule::capacity.staff.title') }}</a></div></div>
<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">{{ __('bookingmodule::settings.auto_assign_heading') }}</h5>

                <form method="POST" action="{{ route('appointment.settings.auto_assign.update') }}">
                    @csrf

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="enabled" value="1" id="enabled" {{ $enabled ? 'checked' : '' }}>
                        <label class="form-check-label" for="enabled">{{ __('bookingmodule::settings.enabled') }}</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('bookingmodule::settings.strategy') }}</label>
                        <select class="form-control" name="strategy">
                            <option value="schedule_match" {{ $strategy==='schedule_match' ? 'selected' : '' }}>{{ __('bookingmodule::settings.strategy_schedule_match') }}</option>
                            <option value="least_busy" {{ $strategy==='least_busy' ? 'selected' : '' }}>{{ __('bookingmodule::settings.strategy_least_busy') }}</option>
                            <option value="round_robin" {{ $strategy==='round_robin' ? 'selected' : '' }}>{{ __('bookingmodule::settings.strategy_round_robin') }}</option>
                        </select>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="require_permission" value="1" id="require_permission" {{ $requirePermission ? 'checked' : '' }}>
                        <label class="form-check-label" for="require_permission">{{ __('bookingmodule::settings.require_permission') }}</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('bookingmodule::settings.eligible_permission') }}</label>
                        <input class="form-control" name="eligible_permission" value="{{ $eligiblePermission }}" />
                        <small class="text-muted">{{ __('bookingmodule::settings.eligible_permission_help') }}</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-primary" type="submit">{{ __('bookingmodule::settings.save') }}</button>
                        <a class="btn btn-light" href="{{ url()->previous() }}">{{ __('bookingmodule::settings.back') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
