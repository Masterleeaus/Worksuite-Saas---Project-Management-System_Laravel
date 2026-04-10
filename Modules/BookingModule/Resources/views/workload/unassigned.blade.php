@extends('layouts.app')

@section('page-title', __('bookingmodule::assignment.labels.unassigned'))

@section('content')
<div class="container-fluid">
    <h3 class="mb-3">{{ __('bookingmodule::assignment.labels.unassigned') }}</h3>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('bookingmodule::assignment.labels.status') }}</th>
                    <th>{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $a)
                    <tr>
                        <td>{{ $a->id }}</td>
                        <td>{{ $a->name }}</td>
                        <td>{{ $a->appointment_type }}</td>
                        <td>@include('bookingmodule::appointment.partials._assignment_badge', ['appointment' => $a])</td>
                        <td><a class="btn btn-sm btn-primary" href="{{ route('appointments.show', $a->id) }}">{{ __('View') }}</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $appointments->links() }}
</div>
@endsection
