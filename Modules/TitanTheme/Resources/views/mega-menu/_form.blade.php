{{-- Shared form fields for mega menu create / edit --}}
<div class="form-group">
    <label class="f-14 text-dark-grey mb-1">@lang('titantheme::titantheme.name') <span class="text-danger">*</span></label>
    <input type="text" name="title" class="form-control f-14"
           value="{{ old('title', $menu->title ?? '') }}" required>
</div>
<div class="form-group">
    <label class="f-14 text-dark-grey mb-1">@lang('titantheme::titantheme.icon')</label>
    <input type="text" name="icon" class="form-control f-14"
           value="{{ old('icon', $menu->icon ?? '') }}" placeholder="fa fa-bars">
</div>
<div class="form-group">
    <label class="f-14 text-dark-grey mb-1">@lang('titantheme::titantheme.sort_order')</label>
    <input type="number" name="sort_order" class="form-control f-14"
           value="{{ old('sort_order', $menu->sort_order ?? 0) }}" min="0">
</div>
<div class="form-group">
    <label class="f-14 text-dark-grey mb-1">@lang('titantheme::titantheme.required_module')</label>
    <input type="text" name="required_module" class="form-control f-14"
           value="{{ old('required_module', $menu->required_module ?? '') }}"
           placeholder="ModuleName (optional)">
</div>
<div class="form-check">
    <input type="checkbox" class="form-check-input" id="is_active_menu" name="is_active" value="1"
           {{ old('is_active', $menu->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label f-14" for="is_active_menu">@lang('titantheme::titantheme.is_active')</label>
</div>
