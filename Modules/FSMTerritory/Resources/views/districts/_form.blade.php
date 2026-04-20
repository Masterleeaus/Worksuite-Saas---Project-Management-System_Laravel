<div class="mb-3">
    <label class="form-label">Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $district->name ?? '') }}" required maxlength="256">
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <input type="text" name="description" class="form-control @error('description') is-invalid @enderror"
           value="{{ old('description', $district->description ?? '') }}" maxlength="512">
    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Region</label>
    <select name="region_id" class="form-select @error('region_id') is-invalid @enderror">
        <option value="">— None —</option>
        @foreach($regions as $region)
            <option value="{{ $region->id }}" {{ old('region_id', $district->region_id ?? '') == $region->id ? 'selected' : '' }}>
                {{ $region->name }}
            </option>
        @endforeach
    </select>
    @error('region_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Manager</label>
    <select name="manager_id" class="form-select @error('manager_id') is-invalid @enderror">
        <option value="">— None —</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}" {{ old('manager_id', $district->manager_id ?? '') == $user->id ? 'selected' : '' }}>
                {{ $user->name }}
            </option>
        @endforeach
    </select>
    @error('manager_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('fsmterritory.districts.index') }}" class="btn btn-secondary">Cancel</a>
</div>
