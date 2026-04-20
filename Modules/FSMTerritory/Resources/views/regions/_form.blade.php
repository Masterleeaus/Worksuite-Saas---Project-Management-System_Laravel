<div class="mb-3">
    <label class="form-label">Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $region->name ?? '') }}" required maxlength="256">
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <input type="text" name="description" class="form-control @error('description') is-invalid @enderror"
           value="{{ old('description', $region->description ?? '') }}" maxlength="512">
    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Manager</label>
    <select name="manager_id" class="form-select @error('manager_id') is-invalid @enderror">
        <option value="">— None —</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}" {{ old('manager_id', $region->manager_id ?? '') == $user->id ? 'selected' : '' }}>
                {{ $user->name }}
            </option>
        @endforeach
    </select>
    @error('manager_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('fsmterritory.regions.index') }}" class="btn btn-secondary">Cancel</a>
</div>
