<div class="form-group">
    <label>Name</label>
    <input class="form-control" name="name" value="{{ old('name', $campaign->name ?? '') }}" required>
</div>

<div class="form-group">
    <label>Status</label>
    <select class="form-control" name="status">
        @php($val = old('status', $campaign->status ?? 'draft'))
        @foreach(['draft','active','paused','archived'] as $s)
            <option value="{{ $s }}" @selected($val === $s)>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Description</label>
    <textarea class="form-control" name="description" rows="3">{{ old('description', $campaign->description ?? '') }}</textarea>
</div>


<div class="form-group">
    <label class="d-flex align-items-center gap-2">
        <input type="checkbox" name="stop_on_reply" value="1" {{ old('stop_on_reply', $campaign->stop_on_reply ?? true) ? 'checked' : '' }}>
        <span>Stop campaign when customer replies (recommended)</span>
    </label>
    <div class="text-muted small">When enabled, any inbound reply will mark future queued deliveries as <strong>skipped</strong> for this campaign.</div>
</div>
