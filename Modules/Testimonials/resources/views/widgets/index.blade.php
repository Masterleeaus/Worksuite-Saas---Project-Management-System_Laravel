@extends('admin.admin')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between mb-3">
            <div class="my-auto mb-2">
                <h3 class="page-title mb-1">{{ __('Testimonial Widgets') }}</h3>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.testimonials') }}">{{ __('Testimonials') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Widgets') }}</li>
                    </ol>
                </nav>
            </div>
            <div>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createWidgetModal">
                    <i class="ti ti-plus me-1"></i>{{ __('Create Widget') }}
                </button>
            </div>
        </div>

        <div class="row">
            @forelse($widgets as $widget)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-1">{{ $widget->name }}</h5>
                        <p class="text-muted mb-2 small">{{ __('Created') }}: {{ $widget->created_at->format('d M Y') }}</p>
                        @if($widget->embed_code)
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">{{ __('Embed Code') }}</label>
                            <textarea class="form-control form-control-sm font-monospace" rows="3" readonly>{{ $widget->embed_code }}</textarea>
                        </div>
                        @endif
                        <div class="d-flex gap-2">
                            <a href="{{ route('testimonials.widget.embed', ['widget' => $widget->id]) }}"
                               target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="ti ti-eye me-1"></i>{{ __('Preview') }}
                            </a>
                            <form method="POST" action="{{ route('admin.testimonials.widgets.destroy', $widget->id) }}"
                                  onsubmit="return confirm('{{ __('Delete this widget?') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="ti ti-trash me-1"></i>{{ __('Delete') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5 text-muted">
                        <i class="ti ti-widget fs-1 d-block mb-2"></i>
                        {{ __('No widgets created yet. Create one to embed testimonials on your external website.') }}
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Create Widget Modal --}}
<div class="modal fade" id="createWidgetModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Create Testimonial Widget') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.testimonials.widgets.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Widget Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required
                               placeholder="{{ __('e.g. Homepage Widget, Blog Sidebar') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Filter by Service Type') }}</label>
                        <input type="text" name="settings_json[service_type]" class="form-control"
                               placeholder="{{ __('e.g. residential (leave blank for all)') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Minimum Star Rating') }}</label>
                        <select name="settings_json[min_rating]" class="form-select">
                            <option value="">{{ __('Any') }}</option>
                            @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}">{{ $i }}{{ $i === 5 ? ' ★ only' : '+ stars' }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Max Items to Show') }}</label>
                        <input type="number" name="settings_json[limit]" class="form-control" value="5" min="1" max="50">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Create Widget') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
