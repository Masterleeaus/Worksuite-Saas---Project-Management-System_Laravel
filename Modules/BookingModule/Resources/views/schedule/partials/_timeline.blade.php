<div class="card mt-3">
    <div class="card-header">
        <h5 class="mb-0">{{ __('bookingmodule::audit.audit_timeline') }}</h5>
    </div>
    <div class="card-body">
        @php($events = $schedule->assignments ?? collect())
        @if($events->isEmpty())
            <div class="text-muted">{{ __('bookingmodule::audit.no_audit_events') }}</div>
        @else
            <ul class="list-group">
                @foreach($events as $event)
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="me-3">
                            <div class="fw-semibold">
                                {{ __('bookingmodule::audit.event_'.$event->action) }}
                            </div>
                            @if(!empty($event->note))
                                <div class="text-muted small">{{ $event->note }}</div>
                            @endif
                        </div>
                        <div class="text-muted small">
                            {{ $event->created_at?->format('Y-m-d H:i') }}
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
