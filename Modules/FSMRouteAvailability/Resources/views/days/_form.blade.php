<div class="mb-3">
    <label for="blackout_group_id" class="form-label">Blackout Group <span class="text-danger">*</span></label>
    <select id="blackout_group_id"
            name="blackout_group_id"
            class="form-select @error('blackout_group_id') is-invalid @enderror"
            required>
        <option value="">— Select Group —</option>
        @foreach($groups as $group)
            <option value="{{ $group->id }}" @selected(old('blackout_group_id', $day->blackout_group_id ?? request('blackout_group_id')) == $group->id)>
                {{ $group->name }}
            </option>
        @endforeach
    </select>
    @error('blackout_group_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
    <input type="date"
           id="date"
           name="date"
           class="form-control @error('date') is-invalid @enderror"
           value="{{ old('date', isset($day) ? $day->date->format('Y-m-d') : '') }}"
           required>
    @error('date')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="label" class="form-label">Label</label>
    <input type="text"
           id="label"
           name="label"
           class="form-control @error('label') is-invalid @enderror"
           value="{{ old('label', $day->label ?? '') }}"
           maxlength="256">
    @error('label')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="zip" class="form-label">Zip Code <small class="text-muted">(leave blank to block all zips)</small></label>
    <input type="text"
           id="zip"
           name="zip"
           class="form-control @error('zip') is-invalid @enderror"
           value="{{ old('zip', $day->zip ?? '') }}"
           maxlength="20">
    @error('zip')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mt-3">
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('fsmrouteavailability.days.index') }}" class="btn btn-secondary ms-2">Cancel</a>
</div>
