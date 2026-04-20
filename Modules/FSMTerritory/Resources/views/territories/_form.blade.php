@php $editing = isset($territory) && $territory; @endphp
<form method="POST" action="{{ $editing ? route('fsmterritory.territories.update', $territory->id) : route('fsmterritory.territories.store') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $editing ? $territory->name : '') }}" required maxlength="256">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Description</label>
        <input type="text" name="description" class="form-control @error('description') is-invalid @enderror"
               value="{{ old('description', $editing ? $territory->description : '') }}" maxlength="512">
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Branch</label>
        <select name="branch_id" class="form-select @error('branch_id') is-invalid @enderror">
            <option value="">— None —</option>
            @foreach($branches as $branch)
                <option value="{{ $branch->id }}" @selected(old('branch_id', $editing ? $territory->branch_id : '') == $branch->id)>
                    {{ $branch->name }}{{ $branch->district ? ' ('.$branch->district->name.')' : '' }}
                </option>
            @endforeach
        </select>
        @error('branch_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Territory Type</label>
        <select name="type" class="form-select @error('type') is-invalid @enderror">
            @foreach(\Modules\FSMTerritory\Models\FSMTerritory::TYPES as $key => $label)
                <option value="{{ $key }}" @selected(old('type', $editing ? $territory->type : 'zip') == $key)>{{ $label }}</option>
            @endforeach
        </select>
        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Zip Codes</label>
        <textarea name="zip_codes" class="form-control @error('zip_codes') is-invalid @enderror" rows="3"
                  placeholder="Enter zip codes separated by commas or newlines">{{ old('zip_codes', $editing ? $territory->zip_codes : '') }}</textarea>
        @error('zip_codes')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <button type="submit" class="btn btn-primary">{{ $editing ? 'Update Territory' : 'Create Territory' }}</button>
    <a href="{{ route('fsmterritory.territories.index') }}" class="btn btn-secondary ms-1">Cancel</a>
</form>
