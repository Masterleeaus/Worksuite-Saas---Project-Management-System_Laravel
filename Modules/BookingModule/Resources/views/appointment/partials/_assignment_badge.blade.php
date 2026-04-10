@php
    $status = $appointment->assignment_status ?? ($appointment->assigned_to ? 'assigned' : 'unassigned');
@endphp
<span class="badge bg-{{ $status === 'assigned' ? 'success' : 'secondary' }}">
    {{ $status === 'assigned' ? __('bookingmodule::assignment.labels.assigned') : __('bookingmodule::assignment.labels.unassigned') }}
</span>
