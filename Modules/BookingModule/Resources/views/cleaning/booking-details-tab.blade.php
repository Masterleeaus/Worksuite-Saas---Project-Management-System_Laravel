{{--
    Booking Details Tab — pushed into @stack('booking-details-tab') in tasks/show.blade.php
    Only rendered when the current task has task_type = 'booking'.

    Usage: include this partial from the BookingModule service provider boot hook
    or from a view composer that targets the tasks/show view.
--}}

@if(isset($task) && $task->task_type === 'booking')
@push('booking-details-tab')
<div class="booking-details-tab mt-4 p-3 border rounded bg-light" id="booking-fsm-panel">
    <h6 class="text-uppercase text-muted mb-3">
        <i class="fa fa-broom mr-1"></i> {{ __('bookingmodule::app.cleaning_booking_details') }}
    </h6>

    <div class="row">
        {{-- Service info --}}
        <div class="col-md-6 mb-2">
            <strong>{{ __('bookingmodule::app.service_type') }}:</strong>
            {{ $task->service_type ? ucwords(str_replace('_', ' ', $task->service_type)) : '—' }}
        </div>
        <div class="col-md-6 mb-2">
            <strong>{{ __('bookingmodule::app.booking_status') }}:</strong>
            <span class="badge badge-{{ \Modules\BookingModule\Models\CleaningBooking::statusBadge($task->booking_status ?? 'pending') }}">
                {{ ucfirst($task->booking_status ?? 'pending') }}
            </span>
        </div>

        {{-- Address --}}
        <div class="col-12 mb-2">
            <strong>{{ __('bookingmodule::app.service_address') }}:</strong>
            {{ $task->service_address ?? '—' }}
            @if($task->service_lat && $task->service_lng)
                <a href="https://maps.google.com/?q={{ $task->service_lat }},{{ $task->service_lng }}"
                   target="_blank" rel="noopener noreferrer" class="ml-1">
                    <i class="fa fa-map-marker-alt text-primary"></i>
                </a>
            @endif
        </div>

        {{-- Property --}}
        <div class="col-md-4 mb-2">
            <strong>{{ __('bookingmodule::app.property_type') }}:</strong>
            {{ $task->property_type ? ucfirst($task->property_type) : '—' }}
        </div>
        <div class="col-md-4 mb-2">
            <strong>{{ __('bookingmodule::app.bedrooms') }}:</strong>
            {{ $task->bedrooms ?? '—' }}
        </div>
        <div class="col-md-4 mb-2">
            <strong>{{ __('bookingmodule::app.bathrooms') }}:</strong>
            {{ $task->bathrooms ?? '—' }}
        </div>

        {{-- Schedule / duration --}}
        <div class="col-md-6 mb-2">
            <strong>{{ __('bookingmodule::app.frequency') }}:</strong>
            {{ $task->frequency ? ucfirst($task->frequency) : '—' }}
        </div>
        <div class="col-md-6 mb-2">
            <strong>{{ __('bookingmodule::app.estimated_hours') }}:</strong>
            {{ $task->estimated_duration_hours ? $task->estimated_duration_hours . 'h' : '—' }}
        </div>

        {{-- Crew --}}
        <div class="col-md-6 mb-2">
            <strong>{{ __('bookingmodule::app.cleaners_required') }}:</strong>
            {{ $task->num_cleaners_required ?? 1 }}
        </div>
        <div class="col-md-6 mb-2">
            <strong>{{ __('bookingmodule::app.supplies_required') }}:</strong>
            {{ $task->supplies_required ? __('app.yes') : __('app.no') }}
        </div>

        {{-- Access --}}
        <div class="col-md-6 mb-2">
            <strong>{{ __('bookingmodule::app.access_method') }}:</strong>
            {{ $task->access_method ? ucwords(str_replace('_', ' ', $task->access_method)) : '—' }}
        </div>

        {{-- Timestamps --}}
        @if($task->cleaner_arrived_at)
        <div class="col-md-6 mb-2">
            <strong>{{ __('bookingmodule::app.cleaner_arrived') }}:</strong>
            {{ $task->cleaner_arrived_at->format('d M Y H:i') }}
        </div>
        @endif
        @if($task->cleaner_departed_at)
        <div class="col-md-6 mb-2">
            <strong>{{ __('bookingmodule::app.cleaner_departed') }}:</strong>
            {{ $task->cleaner_departed_at->format('d M Y H:i') }}
        </div>
        @endif

        {{-- Invoice --}}
        @if($task->invoice_generated && $task->generated_invoice_id)
        <div class="col-12 mt-2">
            <a href="{{ route('invoices.show', $task->generated_invoice_id) }}" class="btn btn-sm btn-outline-primary">
                <i class="fa fa-file-invoice mr-1"></i> {{ __('bookingmodule::app.view_invoice') }}
            </a>
        </div>
        @endif
    </div>
</div>
@endpush
@endif
