<div class="form-group">
    <label>@lang('app.title') <span class="text-danger">*</span></label>
    <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
</div>
<div class="form-group">
    <label>@lang('app.description')</label>
    <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
</div>
<div class="form-group">
    <label>@lang('app.status') <span class="text-danger">*</span></label>
    <select name="status" class="form-control select-picker" required>
        @foreach($statuses as $key => $label)
            <option value="{{ $key }}">{{ $label }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label>Target Date</label>
    <input type="date" name="target_date" class="form-control" value="{{ old('target_date') }}">
</div>
@if(isset($edit) && $edit)
    <div class="form-group">
        <label>Completed Date</label>
        <input type="date" name="completed_date" class="form-control" value="{{ old('completed_date') }}">
    </div>
@endif
