@php $editing = isset($profile) && $profile; @endphp
<form method="POST" action="{{ $editing ? route('fsmequipmentwarranty.profiles.update', $profile->id) : route('fsmequipmentwarranty.profiles.store') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label fw-semibold">Profile Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $editing ? $profile->name : '') }}" required maxlength="256">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Equipment Category</label>
        <input type="text" name="equipment_category" class="form-control @error('equipment_category') is-invalid @enderror"
               value="{{ old('equipment_category', $editing ? $profile->equipment_category : '') }}" maxlength="256">
        @error('equipment_category')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">Warranty Duration <span class="text-danger">*</span></label>
            <input type="number" name="warranty_duration" class="form-control @error('warranty_duration') is-invalid @enderror"
                   value="{{ old('warranty_duration', $editing ? $profile->warranty_duration : 12) }}" min="1" required>
            @error('warranty_duration')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">Warranty Type <span class="text-danger">*</span></label>
            <select name="warranty_type" class="form-select @error('warranty_type') is-invalid @enderror" required>
                @foreach(\Modules\FSMEquipmentWarranty\Models\FSMWarrantyProfile::TYPES as $key => $label)
                    <option value="{{ $key }}" @selected(old('warranty_type', $editing ? $profile->warranty_type : 'month') == $key)>{{ $label }}</option>
                @endforeach
            </select>
            @error('warranty_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Notes</label>
        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $editing ? $profile->notes : '') }}</textarea>
        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <button type="submit" class="btn btn-primary">{{ $editing ? 'Update Profile' : 'Create Profile' }}</button>
    <a href="{{ route('fsmequipmentwarranty.profiles.index') }}" class="btn btn-secondary ms-1">Cancel</a>
</form>
