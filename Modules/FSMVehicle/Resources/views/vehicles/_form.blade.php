<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Vehicle Name / Plate <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" required maxlength="128"
               value="{{ old('name', $vehicle?->name) }}" placeholder="e.g. Van 03 - ABC 123">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">License Plate</label>
        <input type="text" name="license_plate" class="form-control" maxlength="32"
               value="{{ old('license_plate', $vehicle?->license_plate) }}" placeholder="e.g. ABC 123">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Make</label>
        <input type="text" name="make" class="form-control" maxlength="64"
               value="{{ old('make', $vehicle?->make) }}" placeholder="e.g. Toyota">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Model</label>
        <input type="text" name="model" class="form-control" maxlength="64"
               value="{{ old('model', $vehicle?->model) }}" placeholder="e.g. HiAce">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Year</label>
        <input type="number" name="year" class="form-control" min="1900" max="2100"
               value="{{ old('year', $vehicle?->year) }}" placeholder="{{ date('Y') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">VIN</label>
        <input type="text" name="vin" class="form-control" maxlength="64"
               value="{{ old('vin', $vehicle?->vin) }}" placeholder="Vehicle Identification Number">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Primary Driver / Cleaner</label>
        <select name="person_id" class="form-select">
            <option value="">— Unassigned —</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ old('person_id', $vehicle?->person_id) == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Current Mileage (km)</label>
        <input type="number" name="current_mileage" class="form-control" min="0"
               value="{{ old('current_mileage', $vehicle?->current_mileage ?? 0) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Last Service Date</label>
        <input type="date" name="last_service_date" class="form-control"
               value="{{ old('last_service_date', $vehicle?->last_service_date?->format('Y-m-d')) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Next Service at (km)</label>
        <input type="number" name="next_service_mileage" class="form-control" min="0"
               value="{{ old('next_service_mileage', $vehicle?->next_service_mileage) }}" placeholder="e.g. 50000">
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold">Notes</label>
        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $vehicle?->notes) }}</textarea>
    </div>
    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="active" id="active" value="1"
                   {{ old('active', $vehicle ? ($vehicle->active ? '1' : '') : '1') == '1' ? 'checked' : '' }}>
            <label class="form-check-label" for="active">Active</label>
        </div>
    </div>
</div>
