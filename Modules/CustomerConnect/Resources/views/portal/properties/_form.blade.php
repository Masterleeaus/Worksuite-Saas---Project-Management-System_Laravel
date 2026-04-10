{{-- Shared form fields for property create/edit --}}

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mb-3">
    <label for="name" class="form-label fw-semibold">Property Name <span class="text-danger">*</span></label>
    <input type="text" name="name" id="name"
           class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $property->name ?? '') }}"
           placeholder="e.g. Home, Office, Holiday Unit"
           required>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label for="address" class="form-label fw-semibold">Full Address <span class="text-danger">*</span></label>
    <input type="text" name="address" id="address"
           class="form-control @error('address') is-invalid @enderror"
           value="{{ old('address', $property->address ?? '') }}"
           placeholder="Street address, suburb, state, postcode"
           required>
    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label for="property_type" class="form-label fw-semibold">Property Type</label>
        <select name="property_type" id="property_type" class="form-select @error('property_type') is-invalid @enderror">
            <option value="">— Select —</option>
            @foreach(['residential' => 'Residential', 'commercial' => 'Commercial', 'strata' => 'Strata'] as $val => $label)
                <option value="{{ $val }}" {{ old('property_type', $property->property_type ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('property_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="bedrooms" class="form-label fw-semibold">Bedrooms</label>
        <input type="number" name="bedrooms" id="bedrooms" min="0" max="50"
               class="form-control @error('bedrooms') is-invalid @enderror"
               value="{{ old('bedrooms', $property->bedrooms ?? '') }}">
        @error('bedrooms')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="bathrooms" class="form-label fw-semibold">Bathrooms</label>
        <input type="number" name="bathrooms" id="bathrooms" min="0" max="50"
               class="form-control @error('bathrooms') is-invalid @enderror"
               value="{{ old('bathrooms', $property->bathrooms ?? '') }}">
        @error('bathrooms')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3">
    <label for="access_method" class="form-label fw-semibold">Access Method</label>
    <select name="access_method" id="access_method" class="form-select @error('access_method') is-invalid @enderror">
        <option value="">— Select —</option>
        @foreach(['client_present' => 'Client Present', 'key' => 'Key', 'lockbox' => 'Lockbox', 'alarm' => 'Alarm Code'] as $val => $label)
            <option value="{{ $val }}" {{ old('access_method', $property->access_method ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    @error('access_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label for="special_instructions" class="form-label fw-semibold">Special Instructions</label>
    <textarea name="special_instructions" id="special_instructions" rows="4"
              class="form-control @error('special_instructions') is-invalid @enderror"
              placeholder="Access codes, pet info, areas to focus on, cleaning preferences…">{{ old('special_instructions', $property->special_instructions ?? '') }}</textarea>
    @error('special_instructions')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
