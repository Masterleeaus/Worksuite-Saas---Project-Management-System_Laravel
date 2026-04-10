<div class="mb-3">
    <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $item->name ?? '') }}" required maxlength="128">
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Category</label>
    <select name="category_id" class="form-select">
        <option value="">— None —</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ old('category_id', $item->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Description</label>
    <textarea name="description" class="form-control" rows="3">{{ old('description', $item->description ?? '') }}</textarea>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <label class="form-label fw-semibold">Unit</label>
        <input type="text" name="unit" class="form-control" value="{{ old('unit', $item->unit ?? 'units') }}" maxlength="32">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Current Qty</label>
        <input type="number" name="current_qty" class="form-control" step="0.0001" min="0"
               value="{{ old('current_qty', $item->current_qty ?? 0) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Min Qty</label>
        <input type="number" name="min_qty" class="form-control" step="0.0001" min="0"
               value="{{ old('min_qty', $item->min_qty ?? 0) }}">
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Cost Price</label>
        <input type="number" name="cost_price" class="form-control" step="0.0001" min="0"
               value="{{ old('cost_price', $item->cost_price ?? 0) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Supplier</label>
        <input type="text" name="supplier" class="form-control" maxlength="191"
               value="{{ old('supplier', $item->supplier ?? '') }}">
    </div>
</div>

<div class="mb-3 form-check">
    <input type="hidden" name="active" value="0">
    <input type="checkbox" name="active" value="1" id="active" class="form-check-input"
        {{ old('active', ($item->active ?? true) ? '1' : '0') == '1' ? 'checked' : '' }}>
    <label class="form-check-label" for="active">Active</label>
</div>
