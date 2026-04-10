<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" required value="{{ old('name', $location?->name) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Territory</label>
        <select name="territory_id" class="form-select">
            <option value="">— None —</option>
            @foreach($territories as $t)
                <option value="{{ $t->id }}" {{ old('territory_id', $location?->territory_id) == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-8">
        <label class="form-label">Street</label>
        <input type="text" name="street" class="form-control" value="{{ old('street', $location?->street) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">City</label>
        <input type="text" name="city" class="form-control" value="{{ old('city', $location?->city) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">State / Province</label>
        <input type="text" name="state" class="form-control" value="{{ old('state', $location?->state) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">ZIP / Postcode</label>
        <input type="text" name="zip" class="form-control" value="{{ old('zip', $location?->zip) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Country</label>
        <input type="text" name="country" class="form-control" value="{{ old('country', $location?->country) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Latitude</label>
        <input type="number" step="any" name="latitude" class="form-control" value="{{ old('latitude', $location?->latitude) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Longitude</label>
        <input type="number" step="any" name="longitude" class="form-control" value="{{ old('longitude', $location?->longitude) }}">
    </div>
    <div class="col-12">
        <label class="form-label">Site Notes / Access Codes</label>
        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $location?->notes) }}</textarea>
        <small class="text-muted">Sensitive notes are encrypted by TitanZero.</small>
    </div>
    <div class="col-md-3">
        <div class="form-check mt-4">
            <input type="hidden" name="active" value="0">
            <input type="checkbox" name="active" value="1" class="form-check-input" id="active"
                   {{ old('active', $location?->active ?? true) ? 'checked' : '' }}>
            <label for="active" class="form-check-label">Active</label>
        </div>
    </div>
</div>
