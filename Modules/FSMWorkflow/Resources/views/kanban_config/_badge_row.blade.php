@php
    $cfgFields = [
        'show_skills'             => 'Skills',
        'show_stock_status'       => 'Stock',
        'show_vehicle'            => 'Vehicle',
        'show_timesheet_progress' => 'Timesheet',
        'show_warning_overdue'    => 'Overdue ⚑',
        'show_warning_gps'        => 'GPS ⚑',
        'show_warning_photo'      => 'Photo ⚑',
        'show_warning_cert'       => 'Cert ⚑',
        'show_client_rating'      => 'Rating',
        'show_size'               => 'Size',
    ];
@endphp
<div class="d-flex flex-wrap gap-2">
    @foreach($cfgFields as $key => $label)
        @if($config->{$key})
            <span class="badge bg-success">{{ $label }}</span>
        @else
            <span class="badge bg-light text-secondary text-decoration-line-through">{{ $label }}</span>
        @endif
    @endforeach
</div>
