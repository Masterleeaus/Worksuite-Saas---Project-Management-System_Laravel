@extends('admin.admin')

@section('content')

<div class="page-wrapper">
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between mb-3">
            <div class="my-auto mb-2">
                <h3 class="page-title mb-1">{{ __('Testimonials') }}</h3>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);">{{ __('Content') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Testimonials') }}</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex my-xl-auto right-content align-items-center flex-wrap gap-2">
                {{-- Import from ReviewModule --}}
                <button type="button" class="btn btn-outline-secondary btn-sm" id="importReviewsBtn">
                    <i class="ti ti-cloud-download me-1"></i>{{ __('Import 5★ Reviews') }}
                </button>
                {{-- Import from CustomerFeedback --}}
                <button type="button" class="btn btn-outline-secondary btn-sm" id="importFeedbackBtn">
                    <i class="ti ti-message-dots me-1"></i>{{ __('Import Feedback') }}
                </button>
                {{-- Add new testimonial --}}
                @if(isset($permission))
                    @if(hasPermission($permission, 'Testimonials', 'create'))
                    <a href="#" class="btn btn-primary btn-sm" id="add_testimonial_btn" data-bs-toggle="modal"
                        data-bs-target="#add_testimonial_modal">
                        <i class="ti ti-square-rounded-plus-filled me-1"></i>{{ __('Add Testimonial') }}
                    </a>
                    @endif
                @else
                    <a href="#" class="btn btn-primary btn-sm" id="add_testimonial_btn" data-bs-toggle="modal"
                        data-bs-target="#add_testimonial_modal">
                        <i class="ti ti-square-rounded-plus-filled me-1"></i>{{ __('Add Testimonial') }}
                    </a>
                @endif
            </div>
        </div>

        {{-- Service type filter --}}
        @if(isset($serviceTypes) && $serviceTypes->isNotEmpty())
        <div class="mb-3 d-flex gap-2 flex-wrap align-items-center">
            <span class="fw-semibold text-muted me-1">{{ __('Filter by service:') }}</span>
            <button class="btn btn-sm btn-outline-primary service-filter active" data-type="">{{ __('All') }}</button>
            @foreach($serviceTypes as $type)
            <button class="btn btn-sm btn-outline-primary service-filter" data-type="{{ $type }}">{{ ucfirst($type) }}</button>
            @endforeach
        </div>
        @endif

        @php $isVisible = 0; @endphp
        @if(isset($permission))
            @if(hasPermission($permission, 'Testimonials', 'delete'))
                @php $delete = 1; $isVisible = 1; @endphp
            @else
                @php $delete = 0; @endphp
            @endif
            @if(hasPermission($permission, 'Testimonials', 'edit'))
                @php $edit = 1; $isVisible = 1; @endphp
            @else
                @php $edit = 0; @endphp
            @endif
            <div id="has_permission" data-delete="{{ $delete }}" data-edit="{{ $edit }}" data-visible="{{ $isVisible }}"></div>
        @else
            <div id="has_permission" data-delete="1" data-edit="1" data-visible="1"></div>
        @endif

        <div class="card">
            <div class="card-body p-0 py-3">
                <div class="custom-datatable-filter table-responsive">
                    <table class="table table-bordered" id="testimonialsTable" data-empty="{{ __('No testimonials found.') }}">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>{{ __('Customer') }}</th>
                                <th>{{ __('Suburb') }}</th>
                                <th>{{ __('Service') }}</th>
                                <th>{{ __('Rating') }}</th>
                                <th>{{ __('Content') }}</th>
                                <th>{{ __('Photos') }}</th>
                                <th>{{ __('Published') }}</th>
                                <th>{{ __('Featured') }}</th>
                                <th>{{ __('Source') }}</th>
                                <th class="no-sort">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- populated via JS/AJAX --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add / Edit Testimonial Modal --}}
<div class="modal fade" id="add_testimonial_modal">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title testimonial_modal_title"
                    data-add_title="{{ __('Add Testimonial') }}"
                    data-edit_title="{{ __('Edit Testimonial') }}">
                    {{ __('Add Testimonial') }}
                </h4>
                <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <form id="testimonialForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="method" id="method" value="add">
                    <input type="hidden" name="id" id="id">
                    <div class="row g-3">
                        {{-- Customer name --}}
                        <div class="col-md-6">
                            <label class="form-label">{{ __('Customer Name') }}<span class="text-danger"> *</span></label>
                            <input type="text" class="form-control" id="client_name" name="client_name">
                            <span class="text-danger error-text" id="client_name_error"></span>
                        </div>
                        {{-- Position / Role --}}
                        <div class="col-md-6">
                            <label class="form-label">{{ __('Position / Role') }}</label>
                            <input type="text" class="form-control" id="position" name="position">
                        </div>
                        {{-- Suburb --}}
                        <div class="col-md-4">
                            <label class="form-label">{{ __('Suburb') }}</label>
                            <input type="text" class="form-control" id="suburb" name="suburb" placeholder="{{ __('e.g. Bondi, Surry Hills') }}">
                        </div>
                        {{-- Service type --}}
                        <div class="col-md-4">
                            <label class="form-label">{{ __('Service Type') }}</label>
                            <input type="text" class="form-control" id="service_type" name="service_type"
                                list="service_type_list"
                                placeholder="{{ __('e.g. residential, commercial') }}">
                            <datalist id="service_type_list">
                                <option value="residential">
                                <option value="commercial">
                                <option value="end-of-lease">
                                <option value="carpet cleaning">
                                <option value="window cleaning">
                                <option value="office cleaning">
                            </datalist>
                        </div>
                        {{-- Star rating --}}
                        <div class="col-md-4">
                            <label class="form-label">{{ __('Star Rating') }}</label>
                            <select class="form-select" id="star_rating" name="star_rating">
                                @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ $i === 5 ? 'selected' : '' }}>
                                    {{ str_repeat('★', $i) . str_repeat('☆', 5 - $i) }} ({{ $i }} {{ $i === 1 ? 'star' : 'stars' }})
                                </option>
                                @endfor
                            </select>
                        </div>
                        {{-- Testimonial content --}}
                        <div class="col-12">
                            <label class="form-label">{{ __('Testimonial') }}<span class="text-danger"> *</span></label>
                            <textarea class="form-control" rows="5" name="description" id="description"></textarea>
                            <span class="text-danger error-text" id="description_error"></span>
                        </div>
                        {{-- Video URL --}}
                        <div class="col-12">
                            <label class="form-label">{{ __('Video URL') }} <small class="text-muted">(YouTube / Vimeo)</small></label>
                            <input type="url" class="form-control" id="video_url" name="video_url" placeholder="https://youtube.com/...">
                        </div>
                        {{-- Client image --}}
                        <div class="col-md-4">
                            <label class="form-label">{{ __('Client Photo') }}</label>
                            <input type="file" class="form-control" name="client_image" id="client_image" accept="image/*">
                        </div>
                        {{-- Before photo --}}
                        <div class="col-md-4">
                            <label class="form-label">{{ __('Before Photo') }}</label>
                            <input type="file" class="form-control" name="before_photo" id="before_photo" accept="image/*">
                        </div>
                        {{-- After photo --}}
                        <div class="col-md-4">
                            <label class="form-label">{{ __('After Photo') }}</label>
                            <input type="file" class="form-control" name="after_photo" id="after_photo" accept="image/*">
                        </div>
                        {{-- Status toggle --}}
                        <div class="col-12">
                            <div class="modal-status-toggle d-flex align-items-center justify-content-between">
                                <div class="status-title">
                                    <h5>{{ __('Active') }}</h5>
                                </div>
                                <div class="status-toggle modal-status">
                                    <input type="checkbox" id="status" name="status" class="check" value="1" checked>
                                    <label for="status" class="checktoggle"> </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-light me-2" data-bs-dismiss="modal">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn btn-primary save_testimonial_btn">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="delete_testimonial_modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="deleteTestimonialForm">
                <div class="modal-body text-center">
                    <span class="delete-icon"><i class="ti ti-trash-x"></i></span>
                    <h4>{{ __('Confirm Deletion') }}</h4>
                    <p>{{ __('This action cannot be undone.') }}</p>
                    <input type="hidden" name="delete_id" id="delete_id">
                    <div class="d-flex justify-content-center">
                        <a href="javascript:void(0);" class="btn btn-light me-3" data-bs-dismiss="modal">{{ __('Cancel') }}</a>
                        <button type="submit" class="btn btn-danger delete_testimonial_confirm">{{ __('Yes, Delete') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
