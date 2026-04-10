<div class="row g-3">
    <div class="col-md-3">
        <label class="form-label fw-semibold">Warranty Start <span class="text-danger">*</span></label>
        <input type="date" name="warranty_start" class="form-control" required value="{{ old('warranty_start', $warranty?->warranty_start?->format('Y-m-d')) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Warranty End <span class="text-danger">*</span></label>
        <input type="date" name="warranty_end" class="form-control" required value="{{ old('warranty_end', $warranty?->warranty_end?->format('Y-m-d')) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Supplier</label>
        <input type="text" name="supplier" class="form-control" maxlength="256" value="{{ old('supplier', $warranty?->supplier) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Warranty Number</label>
        <input type="text" name="warranty_number" class="form-control" maxlength="128" value="{{ old('warranty_number', $warranty?->warranty_number) }}">
    </div>
    <div class="col-12">
        <label class="form-label">Notes / Terms</label>
        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $warranty?->notes) }}</textarea>
    </div>
</div>
