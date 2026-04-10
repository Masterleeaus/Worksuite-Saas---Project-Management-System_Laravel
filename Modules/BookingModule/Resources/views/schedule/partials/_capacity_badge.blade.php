@php
$cap = $cap ?? null;
@endphp
@if($cap)
    <span class="badge bg-info">
        {{ __('bookingmodule::capacity.staff.max_per_day') }}: {{ $cap['max_per_day'] ?? '-' }},
        {{ __('bookingmodule::capacity.staff.max_per_slot') }}: {{ $cap['max_per_slot'] ?? '-' }}
    </span>
@endif
