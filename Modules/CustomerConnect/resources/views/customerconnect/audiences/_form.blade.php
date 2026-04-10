<div class="form-group">
    <label>Name</label>
    <input class="form-control" name="name" value="{{ old('name', $audience->name ?? '') }}" required>
</div>
<div class="form-group">
    <label>Source</label>
    <input class="form-control" name="source" value="{{ old('source', $audience->source ?? 'manual') }}">
</div>
<div class="form-group">
    <label>Description</label>
    <textarea class="form-control" name="description" rows="3">{{ old('description', $audience->description ?? '') }}</textarea>
</div>
