@extends('layouts.main')

@section('page-title')
    {{ __('bookingmodule::capacity.unassigned.title') }}
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">{{ __('bookingmodule::capacity.unassigned.heading') }}</h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('appointment.schedules.index') }}" class="btn btn-sm btn-secondary">{{ __('Back') }}</a>
                    <a href="{{ route('appointment.schedules.mine') }}" class="btn btn-sm btn-primary">{{ __('bookingmodule::capacity.mine.btn') }}</a>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-2 mb-3">
                    <div class="col-md-3">
                        <input type="date" name="date" value="{{ request('date') }}" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">{{ __('Filter') }}</button>
                    </div>
                </form>

                <form method="POST" action="{{ route('appointment.schedules.bulk_assign') }}">
                    @csrf

                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('bookingmodule::capacity.bulk.assign_to') }}</label>
                            <select name="assigned_to" class="form-select">
                                <option value="">{{ __('bookingmodule::capacity.bulk.unassign') }}</option>
                                @foreach($users as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('bookingmodule::capacity.bulk.note') }}</label>
                            <input type="text" name="note" class="form-control" placeholder="{{ __('bookingmodule::capacity.bulk.note_placeholder') }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-success w-100">{{ __('bookingmodule::capacity.bulk.btn') }}</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="40">
                                        <input type="checkbox" id="select-all">
                                    </th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Time') }}</th>
                                    <th>{{ __('Customer') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('bookingmodule::capacity.assignee') }}</th>
                                    <th>{{ __('bookingmodule::capacity.appointment') }}</th>
                                    <th width="120"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schedules as $s)
                                <tr>
                                    <td><input type="checkbox" name="schedule_ids[]" value="{{ $s->id }}" class="row-check"></td>
                                    <td>{{ $s->date }}</td>
                                    <td>{{ $s->start_time }} - {{ $s->end_time }}</td>
                                    <td>{{ $s->name }}</td>
                                    <td>{{ $s->status }}</td>
                                    <td>{{ optional($s->assignee)->name ?? '-' }}</td>
                                    <td>{{ optional($s->appointment)->title ?? $s->appointment_id }}</td>
                                    <td>
                                        <a href="{{ route('appointment.schedules.action', Crypt::encrypt($s->id)) }}" class="btn btn-sm btn-outline-primary">{{ __('View') }}</a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="8" class="text-center text-muted">{{ __('No records found') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $schedules->links() }}
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('Modules/Appointment/Resources/assets/js/schedule-bulk-assign.js') }}"></script>
@endpush
