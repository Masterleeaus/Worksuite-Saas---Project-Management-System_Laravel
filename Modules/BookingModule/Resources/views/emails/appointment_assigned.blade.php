@component('mail::message')
# {{ __('bookingmodule::assignment.mail.assigned_subject') }}

{{ __('bookingmodule::assignment.mail.assigned_line', ['name' => $appointment->name ?? __('bookingmodule::assignment.labels.appointment')]) }}

@component('mail::button', ['url' => url('/appointments/'.$appointment->id)])
{{ __('bookingmodule::assignment.mail.view') }}
@endcomponent

@endcomponent
