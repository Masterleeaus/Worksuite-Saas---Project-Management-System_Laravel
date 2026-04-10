@php
    $scheduleId = $schedule->id ?? null;
@endphp

<div class="modal-header">
    <h5 class="modal-title">{{ __('bookingmodule::dispatch.quick_edit.title') }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <form id="dispatchQuickEditForm" data-schedule-id="{{ $scheduleId }}">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">{{ __('bookingmodule::dispatch.quick_edit.date') }}</label>
                <input type="date" name="date" class="form-control" value="{{ $schedule->date }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('bookingmodule::dispatch.quick_edit.start_time') }}</label>
                <input type="time" name="start_time" class="form-control" value="{{ $schedule->start_time }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('bookingmodule::dispatch.quick_edit.end_time') }}</label>
                <input type="time" name="end_time" class="form-control" value="{{ $schedule->end_time }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">{{ __('bookingmodule::dispatch.quick_edit.assigned_to') }}</label>
                <input type="number" name="user_id" class="form-control" value="{{ $schedule->assigned_to ?? $schedule->user_id }}">
                <small class="text-muted">{{ __('bookingmodule::dispatch.quick_edit.assigned_to_hint') }}</small>
            </div>

            <div class="col-12">
                <label class="form-label">{{ __('bookingmodule::dispatch.quick_edit.notes') }}</label>
                <textarea class="form-control" name="notes" rows="3">{{ $schedule->notes ?? '' }}</textarea>
            </div>
        </div>
    </form>

    <div class="mt-3 small text-muted" id="dispatchQuickEditError" style="display:none;"></div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('app.close') }}</button>
    <button type="button" class="btn btn-primary" id="dispatchQuickEditSave">{{ __('app.save') }}</button>
</div>
