{{-- Shared form for create/edit size tiers --}}

<div class="row g-3">
    <div class="col-md-3">
        <label class="form-label fw-semibold">Code <span class="text-danger">*</span></label>
        <input type="text" name="code" class="form-control" value="{{ old('code', $size->code ?? '') }}"
               maxlength="8" placeholder="e.g. M" required>
        <div class="form-text">Short identifier: XS, S, M, L, XL</div>
    </div>
    <div class="col-md-9">
        <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $size->name ?? '') }}"
               maxlength="128" placeholder="e.g. Medium" required>
    </div>
</div>

<div class="mb-3 mt-3">
    <label class="form-label">Description</label>
    <input type="text" name="description" class="form-control" value="{{ old('description', $size->description ?? '') }}"
           maxlength="255" placeholder="e.g. 2–4 hours – House clean / small office">
</div>

<div class="mb-3">
    <label class="form-label">Sequence <span class="text-muted">(lower = listed first)</span></label>
    <input type="number" name="sequence" class="form-control" value="{{ old('sequence', $size->sequence ?? 0) }}" min="0" style="width:120px;">
</div>

<div class="mb-3 form-check">
    <input type="checkbox" name="active" id="active" class="form-check-input" value="1"
           {{ old('active', ($size->active ?? true) ? '1' : '0') === '1' ? 'checked' : '' }}>
    <label class="form-check-label" for="active">Active</label>
</div>
