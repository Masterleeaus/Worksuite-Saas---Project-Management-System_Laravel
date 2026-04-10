{{-- Shared frequency form partial --}}
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" required
               value="{{ old('name', $frequency?->name) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Repeat Every <span class="text-danger">*</span></label>
        <input type="number" name="interval" class="form-control" min="1" required
               value="{{ old('interval', $frequency?->interval ?? 1) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Interval Type <span class="text-danger">*</span></label>
        <select name="interval_type" class="form-select" required>
            @foreach($intervalTypes as $key => $label)
                <option value="{{ $key }}" {{ old('interval_type', $frequency?->interval_type) === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <div class="form-check mt-4">
            <input class="form-check-input" type="checkbox" name="active" id="freq_active" value="1"
                   {{ old('active', $frequency?->active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="freq_active">Active</label>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-check mt-4">
            <input class="form-check-input" type="checkbox" name="is_exclusive" id="freq_exclusive" value="1"
                   {{ old('is_exclusive', $frequency?->is_exclusive) ? 'checked' : '' }}>
            <label class="form-check-label" for="freq_exclusive">Exclusive Rule (excludes dates)</label>
        </div>
    </div>

    {{-- By weekday --}}
    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="use_byweekday" id="use_byweekday" value="1"
                   {{ old('use_byweekday', $frequency?->use_byweekday) ? 'checked' : '' }}
                   onchange="toggleSection('weekday_section', this.checked)">
            <label class="form-check-label fw-semibold" for="use_byweekday">Filter by Day of Week</label>
        </div>
        <div id="weekday_section" class="mt-2 ms-3 {{ old('use_byweekday', $frequency?->use_byweekday) ? '' : 'd-none' }}">
            @foreach(['mo' => 'Mon', 'tu' => 'Tue', 'we' => 'Wed', 'th' => 'Thu', 'fr' => 'Fri', 'sa' => 'Sat', 'su' => 'Sun'] as $key => $label)
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="weekday_{{ $key }}" id="wd_{{ $key }}" value="1"
                       {{ old('weekday_'.$key, $frequency?->{'weekday_'.$key}) ? 'checked' : '' }}>
                <label class="form-check-label" for="wd_{{ $key }}">{{ $label }}</label>
            </div>
            @endforeach
        </div>
    </div>

    {{-- By month day --}}
    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="use_bymonthday" id="use_bymonthday" value="1"
                   {{ old('use_bymonthday', $frequency?->use_bymonthday) ? 'checked' : '' }}
                   onchange="toggleSection('monthday_section', this.checked)">
            <label class="form-check-label fw-semibold" for="use_bymonthday">Filter by Day of Month</label>
        </div>
        <div id="monthday_section" class="mt-2 ms-3 {{ old('use_bymonthday', $frequency?->use_bymonthday) ? '' : 'd-none' }}">
            <label class="form-label">Day of Month (1–31)</label>
            <input type="number" name="month_day" class="form-control w-auto" min="1" max="31"
                   value="{{ old('month_day', $frequency?->month_day) }}">
        </div>
    </div>

    {{-- By month --}}
    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="use_bymonth" id="use_bymonth" value="1"
                   {{ old('use_bymonth', $frequency?->use_bymonth) ? 'checked' : '' }}
                   onchange="toggleSection('month_section', this.checked)">
            <label class="form-check-label fw-semibold" for="use_bymonth">Filter by Month</label>
        </div>
        <div id="month_section" class="mt-2 ms-3 {{ old('use_bymonth', $frequency?->use_bymonth) ? '' : 'd-none' }}">
            @foreach(['jan' => 'Jan', 'feb' => 'Feb', 'mar' => 'Mar', 'apr' => 'Apr', 'may' => 'May', 'jun' => 'Jun', 'jul' => 'Jul', 'aug' => 'Aug', 'sep' => 'Sep', 'oct' => 'Oct', 'nov' => 'Nov', 'dec' => 'Dec'] as $key => $label)
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="month_{{ $key }}" id="m_{{ $key }}" value="1"
                       {{ old('month_'.$key, $frequency?->{'month_'.$key}) ? 'checked' : '' }}>
                <label class="form-check-label" for="m_{{ $key }}">{{ $label }}</label>
            </div>
            @endforeach
        </div>
    </div>

    {{-- By set position --}}
    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="use_setpos" id="use_setpos" value="1"
                   {{ old('use_setpos', $frequency?->use_setpos) ? 'checked' : '' }}
                   onchange="toggleSection('setpos_section', this.checked)">
            <label class="form-check-label fw-semibold" for="use_setpos">Filter by Set Position</label>
        </div>
        <div id="setpos_section" class="mt-2 ms-3 {{ old('use_setpos', $frequency?->use_setpos) ? '' : 'd-none' }}">
            <label class="form-label">Position (-366 to 366, e.g. -1 = last)</label>
            <input type="number" name="set_pos" class="form-control w-auto" min="-366" max="366"
                   value="{{ old('set_pos', $frequency?->set_pos) }}">
        </div>
    </div>
</div>

<script>
function toggleSection(id, show) {
    var el = document.getElementById(id);
    if (el) el.classList.toggle('d-none', !show);
}
</script>
