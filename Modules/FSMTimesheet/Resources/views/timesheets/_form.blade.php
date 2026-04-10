<div class="mb-3">
    <label class="form-label">Worker</label>
    <select name="user_id" class="form-select">
        <option value="">— Unassigned —</option>
        @foreach($users as $u)
            <option value="{{ $u->id }}" {{ old('user_id', $line->user_id ?? '') == $u->id ? 'selected' : '' }}>
                {{ $u->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Date <span class="text-danger">*</span></label>
    <input type="date" name="date" class="form-control"
           value="{{ old('date', isset($line) ? $line->date?->format('Y-m-d') : date('Y-m-d')) }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Description / Activity Note</label>
    <input type="text" name="name" class="form-control" maxlength="255"
           value="{{ old('name', $line->name ?? '') }}" placeholder="e.g. Deep cleaning kitchen">
</div>

<div class="row g-2 mb-3">
    <div class="col">
        <label class="form-label">Start Time</label>
        <input type="time" name="start_time" class="form-control" id="ts_start"
               value="{{ old('start_time', isset($line) ? substr($line->start_time ?? '', 0, 5) : '') }}">
    </div>
    <div class="col">
        <label class="form-label">End Time</label>
        <input type="time" name="end_time" class="form-control" id="ts_end"
               value="{{ old('end_time', isset($line) ? substr($line->end_time ?? '', 0, 5) : '') }}">
    </div>
    <div class="col-auto d-flex align-items-end">
        <div class="form-text pt-1" id="ts_computed_display"></div>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Hours (manual override)</label>
    <input type="number" name="unit_amount" id="ts_unit_amount" class="form-control"
           min="0" max="24" step="0.25"
           value="{{ old('unit_amount', $line->unit_amount ?? '') }}"
           placeholder="Auto-calculated from start/end time">
    <div class="form-text">Leave blank to auto-calculate from start and end times.</div>
</div>

<script>
(function () {
    var start = document.getElementById('ts_start');
    var end   = document.getElementById('ts_end');
    var hoursInput = document.getElementById('ts_unit_amount');
    var display = document.getElementById('ts_computed_display');

    if (!start || !end) return;

    function updateComputed() {
        if (!start.value || !end.value) { display.textContent = ''; return; }
        var parseTimeToMinutes = function(t){ var p = t.split(':'); return parseInt(p[0])*60 + parseInt(p[1]); };
        var diff = parseTimeToMinutes(end.value) - parseTimeToMinutes(start.value);
        if (diff <= 0) { display.textContent = ''; return; }
        var h = (diff / 60).toFixed(2);
        display.textContent = '= ' + h + ' h';
        if (!hoursInput.value) { hoursInput.placeholder = h + ' h (auto)'; }
    }

    start.addEventListener('change', updateComputed);
    end.addEventListener('change', updateComputed);
    updateComputed();
})();
</script>
