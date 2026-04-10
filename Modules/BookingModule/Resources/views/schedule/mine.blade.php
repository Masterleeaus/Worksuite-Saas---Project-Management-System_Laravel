@extends('layouts.main')

@section('page-title')
    {{ __('bookingmodule::capacity.mine.title') }}
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">{{ __('bookingmodule::capacity.mine.heading') }}</h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('appointment.schedules.unassigned') }}" class="btn btn-sm btn-warning">{{ __('bookingmodule::capacity.unassigned.btn') }}</a>
                    <a href="{{ route('appointment.schedules.index') }}" class="btn btn-sm btn-secondary">{{ __('Back') }}</a>
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

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Time') }}</th>
                                <th>{{ __('Customer') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('bookingmodule::capacity.appointment') }}</th>
                                <th width="120"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schedules as $s)
                            <tr>
                                <td>{{ $s->date }}</td>
                                <td>{{ $s->start_time }} - {{ $s->end_time }}</td>
                                <td>{{ $s->name }}</td>
                                <td>{{ $s->status }}</td>
                                <td>{{ optional($s->appointment)->title ?? $s->appointment_id }}</td>
                                <td>
                                    <a href="{{ route('appointment.schedules.action', Crypt::encrypt($s->id)) }}" class="btn btn-sm btn-outline-primary">{{ __('View') }}</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted">{{ __('No records found') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $schedules->links() }}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
