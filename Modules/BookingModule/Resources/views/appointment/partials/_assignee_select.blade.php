<div class="form-group">
    <label class="form-label">{{ __('bookingmodule::assignment.labels.assigned_to') }}</label>
    <select name="assigned_to" class="form-control">
        <option value="">{{ __('bookingmodule::assignment.labels.unassigned') }}</option>
        @foreach(($appointmentUsers ?? []) as $u)
            <option value="{{ $u->id }}" {{ (string)old('assigned_to', $selectedAssigneeId ?? '') === (string)$u->id ? 'selected' : '' }}>
                {{ $u->name ?? $u->email }}
            </option>
        @endforeach
    </select>
</div>
