@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h4 class="mb-3">{{ __('bookingmodule::settings.notification_preferences') }}</h4>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('appointment.settings.notification_preferences.update') }}">
                @csrf

                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="mb-3">{{ __('bookingmodule::settings.channels') }}</h5>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="channel_email" id="channel_email" value="1" {{ $pref->channel_email ? 'checked' : '' }}>
                            <label class="form-check-label" for="channel_email">{{ __('bookingmodule::settings.channel_email') }}</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="channel_database" id="channel_database" value="1" {{ $pref->channel_database ? 'checked' : '' }}>
                            <label class="form-check-label" for="channel_database">{{ __('bookingmodule::settings.channel_in_app') }}</label>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="mb-3">{{ __('bookingmodule::settings.events') }}</h5>

                        @foreach ([
                            'notify_assigned' => 'notify_assigned',
                            'notify_reassigned' => 'notify_reassigned',
                            'notify_unassigned' => 'notify_unassigned',
                            'notify_rescheduled' => 'notify_rescheduled',
                            'notify_cancelled' => 'notify_cancelled',
                        ] as $field => $label)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="{{ $field }}" id="{{ $field }}" value="1" {{ $pref->{$field} ? 'checked' : '' }}>
                                <label class="form-check-label" for="{{ $field }}">{{ __('bookingmodule::settings.' . $label) }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="mb-3">{{ __('bookingmodule::settings.digest_and_quiet_hours') }}</h5>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="daily_digest" id="daily_digest" value="1" {{ $pref->daily_digest ? 'checked' : '' }}>
                            <label class="form-check-label" for="daily_digest">{{ __('bookingmodule::settings.daily_digest') }}</label>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">{{ __('bookingmodule::settings.quiet_hours_start') }}</label>
                                <input type="time" name="quiet_hours_start" class="form-control" value="{{ old('quiet_hours_start', $pref->quiet_hours_start) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('bookingmodule::settings.quiet_hours_end') }}</label>
                                <input type="time" name="quiet_hours_end" class="form-control" value="{{ old('quiet_hours_end', $pref->quiet_hours_end) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary">{{ __('bookingmodule::settings.save') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
