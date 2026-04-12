{{-- TitanTheme: Live Customizer --}}
@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center action-bar">
        <h4 class="f-21 font-weight-normal text-capitalize mb-0">
            @lang('titantheme::titantheme.live_customizer')
        </h4>
        <div>
            <a href="{{ route('titantheme.presets.index') }}" class="btn btn-outline-secondary btn-sm mr-2">
                @lang('titantheme::titantheme.theme_presets')
            </a>
            <a href="{{ route('titantheme.white-label.index') }}" class="btn btn-outline-secondary btn-sm">
                @lang('titantheme::titantheme.white_label')
            </a>
        </div>
    </div>

    <div class="row mt-3">
        {{-- Controls panel --}}
        <div class="col-lg-4 col-12">
            <div class="card">
                <div class="card-body">
                    <form id="customizer-form">
                        @csrf

                        <div class="form-group">
                            <label class="f-14 text-dark-grey mb-1">@lang('titantheme::titantheme.name') <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="preset-name" class="form-control f-14"
                                   value="{{ $activePreset->name ?? __('app.default') }}" required>
                        </div>

                        <hr>
                        <h6 class="f-14 font-weight-bold mb-3">Colours</h6>

                        @foreach([
                            'primary_color'    => __('titantheme::titantheme.primary_color'),
                            'secondary_color'  => __('titantheme::titantheme.secondary_color'),
                            'accent_color'     => __('titantheme::titantheme.accent_color'),
                            'background_color' => __('titantheme::titantheme.background_color'),
                            'text_color'       => __('titantheme::titantheme.text_color'),
                        ] as $field => $label)
                        <div class="form-group d-flex align-items-center">
                            <label class="f-14 text-dark-grey mb-0 flex-grow-1">{{ $label }}</label>
                            <input type="color" name="{{ $field }}" class="tt-color-input"
                                   value="{{ $activePreset->$field ?? $defaults[$field] ?? '#000000' }}"
                                   style="width:40px;height:32px;padding:2px;border:1px solid #dee2e6;border-radius:4px;">
                        </div>
                        @endforeach

                        <hr>
                        <h6 class="f-14 font-weight-bold mb-3">Typography</h6>

                        @foreach([
                            'heading_font' => __('titantheme::titantheme.heading_font'),
                            'body_font'    => __('titantheme::titantheme.body_font'),
                        ] as $field => $label)
                        <div class="form-group">
                            <label class="f-14 text-dark-grey mb-1">{{ $label }}</label>
                            <select name="{{ $field }}" class="form-control f-14 tt-font-select">
                                @foreach($availableFonts as $font)
                                <option value="{{ $font }}"
                                    {{ ($activePreset->$field ?? $defaults[$field] ?? '') === $font ? 'selected' : '' }}>
                                    {{ $font }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @endforeach

                        <hr>
                        <h6 class="f-14 font-weight-bold mb-3">Layout</h6>

                        <div class="form-group">
                            <label class="f-14 text-dark-grey mb-1">@lang('titantheme::titantheme.sidebar_width')</label>
                            <input type="number" name="sidebar_width" class="form-control f-14"
                                   value="{{ $activePreset->sidebar_width ?? $defaults['sidebar_width'] ?? 260 }}"
                                   min="160" max="400">
                        </div>
                        <div class="form-group">
                            <label class="f-14 text-dark-grey mb-1">@lang('titantheme::titantheme.header_height')</label>
                            <input type="number" name="header_height" class="form-control f-14"
                                   value="{{ $activePreset->header_height ?? $defaults['header_height'] ?? 64 }}"
                                   min="40" max="120">
                        </div>
                        <div class="form-group">
                            <label class="f-14 text-dark-grey mb-1">@lang('titantheme::titantheme.border_radius')</label>
                            <input type="number" name="border_radius" class="form-control f-14"
                                   value="{{ $activePreset->border_radius ?? $defaults['border_radius'] ?? 6 }}"
                                   min="0" max="50">
                        </div>

                        <hr>
                        <h6 class="f-14 font-weight-bold mb-3">@lang('titantheme::titantheme.custom_css')</h6>
                        <div class="form-group">
                            <textarea name="custom_css" class="form-control f-14" rows="6"
                                      placeholder="/* custom CSS */">{{ $activePreset->custom_css ?? '' }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" id="btn-preview" class="btn btn-outline-primary btn-sm">
                                @lang('titantheme::titantheme.preview')
                            </button>
                            <button type="button" id="btn-save" class="btn btn-primary btn-sm">
                                @lang('titantheme::titantheme.save_theme')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Live preview frame --}}
        <div class="col-lg-8 col-12">
            <div class="card" style="min-height:600px;">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span class="f-14 font-weight-bold">Live Preview</span>
                    <span id="preview-status" class="badge badge-secondary f-12">No changes applied</span>
                </div>
                <div class="card-body p-0">
                    {{-- Inject current CSS vars into a style block that can be swapped on preview --}}
                    <style id="tt-preview-style">
                        {!! $cssVars !!}
                    </style>

                    <div id="tt-preview-panel" class="p-4" style="background: var(--tt-bg, #F5F7FA); min-height:560px;">
                        <div style="background:#fff; border-radius: var(--tt-radius, 6px); padding:24px; margin-bottom:16px; box-shadow:0 2px 6px rgba(0,0,0,.08);">
                            <h5 style="color: var(--tt-primary, #4B6EF5); font-family: var(--tt-font-head, Inter, sans-serif);">
                                Sample Heading
                            </h5>
                            <p style="color: var(--tt-text, #343A40); font-family: var(--tt-font-body, Inter, sans-serif);">
                                This is body text using your chosen body font and text colour. Adjust the controls on the left and click Preview to see changes instantly.
                            </p>
                            <button class="btn btn-sm" style="background: var(--tt-primary, #4B6EF5); color:#fff; border-radius: var(--tt-radius, 6px);">
                                Primary Button
                            </button>
                            <button class="btn btn-sm ml-2" style="background: var(--tt-secondary, #6C757D); color:#fff; border-radius: var(--tt-radius, 6px);">
                                Secondary
                            </button>
                            <button class="btn btn-sm ml-2" style="background: var(--tt-accent, #F0AD4E); color:#fff; border-radius: var(--tt-radius, 6px);">
                                Accent
                            </button>
                        </div>
                        <div style="display:flex; gap:12px;">
                            <div style="width: var(--tt-sidebar-w, 260px); background: var(--tt-primary, #4B6EF5); border-radius: var(--tt-radius, 6px); padding:16px; color:#fff; min-height:200px;">
                                <div class="f-12 mb-2 font-weight-bold">Sidebar</div>
                                <div class="f-12 mb-1">Dashboard</div>
                                <div class="f-12 mb-1">Projects</div>
                                <div class="f-12 mb-1">Clients</div>
                            </div>
                            <div style="flex:1; background:#fff; border-radius: var(--tt-radius, 6px); padding:16px;">
                                <div style="background: var(--tt-primary, #4B6EF5); height: var(--tt-header-h, 64px); border-radius: var(--tt-radius, 6px); margin-bottom:12px; display:flex; align-items:center; padding:0 16px; color:#fff;">
                                    Header ({{ $activePreset->header_height ?? ($defaults['header_height'] ?? 64) }}px)
                                </div>
                                <p class="f-12" style="color: var(--tt-text, #343A40);">Main content area</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    var previewBtn = document.getElementById('btn-preview');
    var saveBtn    = document.getElementById('btn-save');
    var status     = document.getElementById('preview-status');
    var styleEl    = document.getElementById('tt-preview-style');

    function getFormData() {
        var form = document.getElementById('customizer-form');
        return Object.fromEntries(new FormData(form));
    }

    previewBtn.addEventListener('click', function () {
        status.textContent = 'Loading…';
        status.className   = 'badge badge-warning f-12';

        axios.post('{{ route('titantheme.customizer.preview') }}', getFormData())
            .then(function (res) {
                if (res.data && res.data.css) {
                    styleEl.textContent = res.data.css;
                    status.textContent  = 'Preview applied';
                    status.className    = 'badge badge-success f-12';
                }
            })
            .catch(function () {
                status.textContent = 'Preview failed';
                status.className   = 'badge badge-danger f-12';
            });
    });

    saveBtn.addEventListener('click', function () {
        var name = document.getElementById('preset-name').value.trim();
        if (!name) {
            toastr.error('Please enter a preset name.');
            return;
        }

        axios.post('{{ route('titantheme.customizer.save') }}', getFormData())
            .then(function (res) {
                if (res.data && res.data.status === 'success') {
                    toastr.success(res.data.message || '@lang('titantheme::titantheme.theme_saved')');
                }
            })
            .catch(function () {
                toastr.error('Could not save theme.');
            });
    });
}());
</script>
@endpush
