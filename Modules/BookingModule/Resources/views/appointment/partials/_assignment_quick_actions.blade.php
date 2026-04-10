@if(\Modules\BookingModule\Support\AppointmentPermission::check(Auth::user(), 'appointments assign'))
    <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-sm btn-outline-primary">
        {{ __('bookingmodule::assignment.actions.assign') }}
    </a>
@endif
