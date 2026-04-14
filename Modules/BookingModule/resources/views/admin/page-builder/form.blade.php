@extends('adminmodule::layouts.master')

@section('title', 'Booking Page Form')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 gap-3 flex-wrap">
        <div>
            <h2 class="page-title mb-1">{{ $page->exists ? 'Edit booking page' : 'Create booking page' }}</h2>
            <p class="text-muted mb-0">Use the Booking module itself as the booking page creator.</p>
        </div>
        <a href="{{ route('admin.booking.pages.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>

    <form action="{{ $formAction }}" method="POST">
        @csrf
        @if($method !== 'POST') @method($method) @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title', $page->title) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Slug</label>
                                <input type="text" name="slug" class="form-control" value="{{ old('slug', $page->slug) }}" placeholder="auto-from-title">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    @foreach(['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived'] as $value => $label)
                                        <option value="{{ $value }}" @selected(old('status', $page->status) === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Template</label>
                                <select name="template" class="form-control">
                                    @foreach($templateOptions as $key => $template)
                                        <option value="{{ $key }}" @selected(old('template', $page->template) === $key)>{{ $template['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Headline</label>
                                <input type="text" name="headline" class="form-control" value="{{ old('headline', $page->headline) }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Subheadline</label>
                                <textarea name="subheadline" class="form-control" rows="3">{{ old('subheadline', $page->subheadline) }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Hero badge</label>
                                <input type="text" name="hero_badge" class="form-control" value="{{ old('hero_badge', $page->hero_badge) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Primary button label</label>
                                <input type="text" name="primary_button_label" class="form-control" value="{{ old('primary_button_label', $page->primary_button_label) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Primary button URL</label>
                                <input type="text" name="primary_button_url" class="form-control" value="{{ old('primary_button_url', $page->primary_button_url) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Secondary button label</label>
                                <input type="text" name="secondary_button_label" class="form-control" value="{{ old('secondary_button_label', $page->secondary_button_label) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Secondary button URL</label>
                                <input type="text" name="secondary_button_url" class="form-control" value="{{ old('secondary_button_url', $page->secondary_button_url) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Page content</h5>
                        <div class="mb-3">
                            <label class="form-label">Services (one per line)</label>
                            <textarea name="service_lines" class="form-control" rows="6">{{ old('service_lines', $page->service_lines) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Trust bullets (one per line)</label>
                            <textarea name="trust_lines" class="form-control" rows="5">{{ old('trust_lines', $page->trust_lines) }}</textarea>
                        </div>
                        <div>
                            <label class="form-label">FAQ lines (Question | Answer)</label>
                            <textarea name="faq_lines" class="form-control" rows="6">{{ old('faq_lines', $page->faq_lines) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">SEO + theme</h5>
                        <div class="mb-3">
                            <label class="form-label">Meta title</label>
                            <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $page->meta_title) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Meta description</label>
                            <textarea name="meta_description" class="form-control" rows="3">{{ old('meta_description', $page->meta_description) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Accent color</label>
                            <input type="text" name="accent_color" class="form-control" value="{{ old('accent_color', data_get($page->theme, 'accent', '#2563eb')) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Surface color</label>
                            <input type="text" name="surface_color" class="form-control" value="{{ old('surface_color', data_get($page->theme, 'surface', '#0f172a')) }}">
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Soft color</label>
                            <input type="text" name="soft_color" class="form-control" value="{{ old('soft_color', data_get($page->theme, 'soft', '#e2e8f0')) }}">
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn--primary">{{ $page->exists ? 'Save page' : 'Create page' }}</button>
                    @if($page->exists)
                        <a href="{{ route('admin.booking.pages.preview', $page->slug) }}" class="btn btn-outline-primary">Preview page</a>
                    @endif
                </div>
            </div>
        </div>
    </form>
@endsection
