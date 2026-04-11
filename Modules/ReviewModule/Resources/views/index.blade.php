@extends('layouts.app')

@push('datatable-styles')
    @include('sections.datatable_css')
@endpush

@section('filter-section')
    <x-filters.filter-box>
        {{-- Status Filter --}}
        <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">@lang('app.status')</p>
            <div class="select-status">
                <select class="form-control select-picker" name="status" id="review-status">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>@lang('app.all')</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>@lang('reviewmodule::modules.pending')</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>@lang('reviewmodule::modules.approved')</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>@lang('reviewmodule::modules.published')</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>@lang('reviewmodule::modules.rejected')</option>
                </select>
            </div>
        </div>

        {{-- Rating Filter --}}
        <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">@lang('reviewmodule::modules.rating')</p>
            <div class="select-status">
                <select class="form-control select-picker" name="rating" id="review-rating">
                    <option value="">@lang('app.all')</option>
                    @for ($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} ★</option>
                    @endfor
                </select>
            </div>
        </div>

        {{-- Search --}}
        <div class="task-search d-flex py-1 px-lg-3 px-0 border-right-grey align-items-center">
            <form class="w-100 mr-1 mr-lg-0 mr-md-1 ml-md-1 ml-0 ml-lg-0">
                <div class="input-group bg-grey rounded">
                    <div class="input-group-prepend">
                        <span class="input-group-text border-0 bg-additional-grey">
                            <i class="fa fa-search f-13 text-dark-grey"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control f-14 p-1 border-additional-grey"
                        id="search-text-field" placeholder="@lang('app.startTyping')">
                </div>
            </form>
        </div>

        {{-- Reset --}}
        <div class="select-box d-flex py-1 px-lg-2 px-md-2 px-0">
            <x-forms.button-secondary class="btn-xs d-none" id="reset-filters" icon="times-circle">
                @lang('app.clearFilters')
            </x-forms.button-secondary>
        </div>
    </x-filters.filter-box>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar">
            <div class="d-flex flex-wrap" id="table-actions">
                <x-table-button-group>
                </x-table-button-group>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="row mb-3">
            <div class="col-md-2 col-sm-6 mb-2">
                <div class="card text-center shadow-sm">
                    <div class="card-body p-3">
                        <h4 class="mb-0">{{ $stats['total'] }}</h4>
                        <small class="text-muted">@lang('reviewmodule::modules.total_reviews')</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-2">
                <div class="card text-center shadow-sm border-warning">
                    <div class="card-body p-3">
                        <h4 class="mb-0 text-warning">{{ $stats['pending'] }}</h4>
                        <small class="text-muted">@lang('reviewmodule::modules.pending')</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-2">
                <div class="card text-center shadow-sm border-info">
                    <div class="card-body p-3">
                        <h4 class="mb-0 text-info">{{ $stats['approved'] }}</h4>
                        <small class="text-muted">@lang('reviewmodule::modules.approved')</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-2">
                <div class="card text-center shadow-sm border-success">
                    <div class="card-body p-3">
                        <h4 class="mb-0 text-success">{{ $stats['published'] }}</h4>
                        <small class="text-muted">@lang('reviewmodule::modules.published')</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-2">
                <div class="card text-center shadow-sm border-danger">
                    <div class="card-body p-3">
                        <h4 class="mb-0 text-danger">{{ $stats['rejected'] }}</h4>
                        <small class="text-muted">@lang('reviewmodule::modules.rejected')</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-2">
                <div class="card text-center shadow-sm border-primary">
                    <div class="card-body p-3">
                        <h4 class="mb-0 text-primary">{{ $stats['avg_rating'] }} ★</h4>
                        <small class="text-muted">@lang('reviewmodule::modules.avg_rating')</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Reviews Table --}}
        <div class="d-flex flex-column w-tables rounded mt-3 bg-white">
            <x-datatable.table class="border-0">
                <x-slot name="thead">
                    <tr>
                        <th>#</th>
                        <th>@lang('reviewmodule::modules.customer')</th>
                        <th>@lang('reviewmodule::modules.service')</th>
                        <th>@lang('reviewmodule::modules.rating')</th>
                        <th>@lang('reviewmodule::modules.comment')</th>
                        <th>@lang('app.status')</th>
                        <th>@lang('app.date')</th>
                        <th class="text-right">@lang('app.action')</th>
                    </tr>
                </x-slot>

                <x-slot name="tbody">
                    @forelse ($reviews as $review)
                        <tr>
                            <td>{{ $review->readable_id ?? $loop->iteration }}</td>
                            <td>
                                @if ($review->customer)
                                    {{ $review->customer->name }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if ($review->service)
                                    {{ $review->service->name }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= $review->review_rating ? 'text-warning' : 'text-muted' }}">★</span>
                                @endfor
                            </td>
                            <td>{{ \Str::limit($review->review_comment, 60) }}</td>
                            <td>
                                @php
                                    $statusClass = match($review->moderation_status ?? 'pending') {
                                        'published' => 'badge-success',
                                        'approved'  => 'badge-info',
                                        'rejected'  => 'badge-danger',
                                        default     => 'badge-warning',
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst($review->moderation_status ?? 'pending') }}
                                </span>
                            </td>
                            <td>{{ $review->created_at?->format(company()->date_format ?? 'Y-m-d') }}</td>
                            <td class="text-right">
                                <a href="{{ route('reviews.show', $review->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-eye"></i>
                                </a>
                                @if(user()->permission('moderate_reviews') != 'none')
                                    @if(($review->moderation_status ?? 'pending') === 'pending')
                                        <button class="btn btn-sm btn-outline-success review-approve"
                                                data-url="{{ route('reviews.approve', $review->id) }}">
                                            <i class="fa fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger review-reject"
                                                data-url="{{ route('reviews.reject', $review->id) }}">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    @endif
                                    @if(($review->moderation_status ?? 'pending') === 'approved' && user()->permission('publish_review') != 'none')
                                        <button class="btn btn-sm btn-outline-primary review-publish"
                                                data-url="{{ route('reviews.publish', $review->id) }}">
                                            <i class="fa fa-globe"></i>
                                        </button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                @lang('reviewmodule::modules.no_reviews_found')
                            </td>
                        </tr>
                    @endforelse
                </x-slot>
            </x-datatable.table>

            <div class="d-flex justify-content-between align-items-center px-3 pb-3">
                <div>
                    @lang('app.showing') {{ $reviews->firstItem() ?? 0 }}–{{ $reviews->lastItem() ?? 0 }}
                    @lang('app.of') {{ $reviews->total() }}
                </div>
                {{ $reviews->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '.review-approve', function () {
            $.post($(this).data('url'), {_token: '{{ csrf_token() }}'})
                .done(function () { location.reload(); });
        });

        $(document).on('click', '.review-reject', function () {
            $.post($(this).data('url'), {_token: '{{ csrf_token() }}'})
                .done(function () { location.reload(); });
        });

        $(document).on('click', '.review-publish', function () {
            $.post($(this).data('url'), {_token: '{{ csrf_token() }}'})
                .done(function () { location.reload(); });
        });

        $('#review-status, #review-rating').on('change', function () {
            const params = new URLSearchParams();
            const status = $('#review-status').val();
            const rating = $('#review-rating').val();
            if (status && status !== 'all') params.set('status', status);
            if (rating) params.set('rating', rating);
            const qs = params.toString();
            window.location.href = "{{ route('reviews.index') }}" + (qs ? '?' + qs : '');
        });

        let searchTimer;
        $('#search-text-field').on('keyup', function () {
            clearTimeout(searchTimer);
            const q = $(this).val();
            if (q.length > 0) $('#reset-filters').removeClass('d-none');
            else $('#reset-filters').addClass('d-none');
            searchTimer = setTimeout(function () {
                window.location.href = "{{ route('reviews.index') }}" + (q ? '?search=' + encodeURIComponent(q) : '');
            }, 500);
        });

        $('#reset-filters').on('click', function () {
            window.location.href = "{{ route('reviews.index') }}";
        });
    </script>
@endpush
