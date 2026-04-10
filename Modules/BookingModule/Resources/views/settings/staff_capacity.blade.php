@extends('layouts.main')

@section('page-title')
    {{ __('bookingmodule::capacity.staff.title') }}
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">{{ __('bookingmodule::capacity.staff.heading') }}</h5>
                <div>
                    <a href="{{ route('appointment.settings.auto_assign') }}" class="btn btn-sm btn-secondary">{{ __('bookingmodule::settings.auto_assign.title') }}</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('Staff') }}</th>
                                <th>{{ __('bookingmodule::capacity.staff.max_per_day') }}</th>
                                <th>{{ __('bookingmodule::capacity.staff.max_per_slot') }}</th>
                                <th>{{ __('bookingmodule::capacity.staff.enforce_conflicts') }}</th>
                                <th>{{ __('bookingmodule::capacity.staff.count_pending_too') }}</th>
                                <th width="180"></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $u)
                            @php $cap = $capacities[$u->id] ?? null; @endphp
                            <tr>
                                <td>{{ $u->name }}</td>
                                <td>{{ $cap?->max_per_day ?? '-' }}</td>
                                <td>{{ $cap?->max_per_slot ?? '-' }}</td>
                                <td>{{ $cap?->enforce_conflicts ? __('Yes') : __('No') }}</td>
                                <td>{{ $cap?->count_pending_too ? __('Yes') : __('No') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#cap-{{ $u->id }}">{{ __('Edit') }}</button>
                                </td>
                            </tr>
                            <tr class="collapse" id="cap-{{ $u->id }}">
                                <td colspan="6">
                                    <form method="POST" action="{{ route('appointment.settings.staff_capacity.update') }}" class="row g-2">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $u->id }}">
                                        <div class="col-md-2">
                                            <label class="form-label">{{ __('bookingmodule::capacity.staff.max_per_day') }}</label>
                                            <input type="number" name="max_per_day" class="form-control" value="{{ old('max_per_day', $cap?->max_per_day) }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">{{ __('bookingmodule::capacity.staff.max_per_slot') }}</label>
                                            <input type="number" name="max_per_slot" class="form-control" value="{{ old('max_per_slot', $cap?->max_per_slot) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label d-block">{{ __('bookingmodule::capacity.staff.enforce_conflicts') }}</label>
                                            <input type="hidden" name="enforce_conflicts" value="0">
                                            <label class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="enforce_conflicts" value="1" {{ ($cap?->enforce_conflicts ?? true) ? 'checked' : '' }}>
                                                <span class="form-check-label">{{ __('bookingmodule::capacity.staff.enforce_conflicts_help') }}</span>
                                            </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label d-block">{{ __('bookingmodule::capacity.staff.count_pending_too') }}</label>
                                            <input type="hidden" name="count_pending_too" value="0">
                                            <label class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="count_pending_too" value="1" {{ ($cap?->count_pending_too ?? false) ? 'checked' : '' }}>
                                                <span class="form-check-label">{{ __('bookingmodule::capacity.staff.count_pending_too_help') }}</span>
                                            </label>
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button class="btn btn-success w-100">{{ __('Save') }}</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-info mt-3">
                    {{ __('bookingmodule::capacity.staff.note') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
