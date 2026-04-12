{{-- Shared form fields for create / edit preset --}}
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="f-14 text-dark-grey mb-1">@lang('titantheme::titantheme.name') <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control f-14"
                   value="{{ old('name', $preset->name ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="f-14 text-dark-grey mb-1">@lang('titantheme::titantheme.description')</label>
            <input type="text" name="description" class="form-control f-14"
                   value="{{ old('description', $preset->description ?? '') }}">
        </div>
    </div>
</div>

<h6 class="f-14 font-weight-bold mt-3 mb-2">Colours</h6>
<div class="row">
    @foreach([
        'primary_color'    => __('titantheme::titantheme.primary_color'),
        'secondary_color'  => __('titantheme::titantheme.secondary_color'),
        'accent_color'     => __('titantheme::titantheme.accent_color'),
        'background_color' => __('titantheme::titantheme.background_color'),
        'text_color'       => __('titantheme::titantheme.text_color'),
    ] as $field => $label)
    <div class="col-md-4 col-6">
        <div class="form-group">
            <label class="f-14 text-dark-grey mb-1">{{ $label }}</label>
            <div class="d-flex align-items-center">
                <input type="color" name="{{ $field }}"
                       value="{{ old($field, $preset->$field ?? '#000000') }}"
                       style="width:40px;height:32px;padding:2px;border:1px solid #dee2e6;border-radius:4px;margin-right:8px;">
                <input type="text" name="{{ $field }}_text" class="form-control f-14"
                       value="{{ old($field, $preset->$field ?? '') }}"
                       placeholder="#rrggbb"
                       style="max-width:100px;">
            </div>
        </div>
    </div>
    @endforeach
</div>

<h6 class="f-14 font-weight-bold mt-3 mb-2">Typography</h6>
<div class="row">
    @foreach([
        'heading_font' => __('titantheme::titantheme.heading_font'),
        'body_font'    => __('titantheme::titantheme.body_font'),
    ] as $field => $label)
    <div class="col-md-4">
        <div class="form-group">
            <label class="f-14 text-dark-grey mb-1">{{ $label }}</label>
            <select name="{{ $field }}" class="form-control f-14">
                @foreach($availableFonts as $font)
                <option value="{{ $font }}"
                    {{ old($field, $preset->$field ?? '') === $font ? 'selected' : '' }}>
                    {{ $font }}
                </option>
                @endforeach
            </select>
        </div>
    </div>
    @endforeach
</div>

<h6 class="f-14 font-weight-bold mt-3 mb-2">Layout</h6>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label class="f-14 text-dark-grey mb-1">@lang('titantheme::titantheme.sidebar_width')</label>
            <input type="number" name="sidebar_width" class="form-control f-14"
                   value="{{ old('sidebar_width', $preset->sidebar_width ?? 260) }}"
                   min="160" max="400">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="f-14 text-dark-grey mb-1">@lang('titantheme::titantheme.header_height')</label>
            <input type="number" name="header_height" class="form-control f-14"
                   value="{{ old('header_height', $preset->header_height ?? 64) }}"
                   min="40" max="120">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="f-14 text-dark-grey mb-1">@lang('titantheme::titantheme.border_radius')</label>
            <input type="number" name="border_radius" class="form-control f-14"
                   value="{{ old('border_radius', $preset->border_radius ?? 6) }}"
                   min="0" max="50">
        </div>
    </div>
</div>

<h6 class="f-14 font-weight-bold mt-3 mb-2">@lang('titantheme::titantheme.custom_css')</h6>
<div class="form-group">
    <textarea name="custom_css" class="form-control f-14" rows="8"
              placeholder="/* Add any additional CSS here */">{{ old('custom_css', $preset->custom_css ?? '') }}</textarea>
</div>
