{{-- Shared recurring form partial --}}
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Recurring Template</label>
        <select name="recurring_template_id" class="form-select">
            <option value="">— None —</option>
            @foreach($recurringTemplates as $tpl)
                <option value="{{ $tpl->id }}" {{ old('recurring_template_id', $recurring?->recurring_template_id) == $tpl->id ? 'selected' : '' }}>{{ $tpl->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Frequency Set</label>
        <select name="frequency_set_id" class="form-select">
            <option value="">— None —</option>
            @foreach($frequencySets as $fs)
                <option value="{{ $fs->id }}" {{ old('frequency_set_id', $recurring?->frequency_set_id) == $fs->id ? 'selected' : '' }}>{{ $fs->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Location</label>
        <select name="location_id" class="form-select">
            <option value="">— None —</option>
            @foreach($locations as $loc)
                <option value="{{ $loc->id }}" {{ old('location_id', $recurring?->location_id) == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Team</label>
        <select name="team_id" class="form-select">
            <option value="">— None —</option>
            @foreach($teams as $team)
                <option value="{{ $team->id }}" {{ old('team_id', $recurring?->team_id) == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Assigned Worker</label>
        <select name="person_id" class="form-select">
            <option value="">— None —</option>
            @foreach($workers as $worker)
                <option value="{{ $worker->id }}" {{ old('person_id', $recurring?->person_id) == $worker->id ? 'selected' : '' }}>{{ $worker->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Order Template</label>
        <select name="fsm_template_id" class="form-select">
            <option value="">— None —</option>
            @foreach($templates as $tpl)
                <option value="{{ $tpl->id }}" {{ old('fsm_template_id', $recurring?->fsm_template_id) == $tpl->id ? 'selected' : '' }}>{{ $tpl->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Scheduled Duration (hours)</label>
        <input type="number" step="0.25" name="scheduled_duration" class="form-control" min="0"
               value="{{ old('scheduled_duration', $recurring?->scheduled_duration) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Max Orders <small class="text-muted">(0 = unlimited)</small></label>
        <input type="number" name="max_orders" class="form-control" min="0"
               value="{{ old('max_orders', $recurring?->max_orders ?? 0) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Start Date</label>
        <input type="datetime-local" name="start_date" class="form-control"
               value="{{ old('start_date', $recurring?->start_date?->format('Y-m-d\TH:i')) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">End Date <small class="text-muted">(leave blank = forever)</small></label>
        <input type="datetime-local" name="end_date" class="form-control"
               value="{{ old('end_date', $recurring?->end_date?->format('Y-m-d\TH:i')) }}">
    </div>
    <div class="col-12">
        <label class="form-label">Description / Notes</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $recurring?->description) }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label">Equipment</label>
        <select name="equipment_ids[]" class="form-select" multiple>
            @foreach($equipment as $eq)
                <option value="{{ $eq->id }}" {{ in_array($eq->id, old('equipment_ids', $recurring?->equipment?->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>{{ $eq->name }}</option>
            @endforeach
        </select>
        <small class="text-muted">Hold Ctrl/Cmd to select multiple.</small>
    </div>
</div>
