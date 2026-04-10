@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar">
            <div class="d-flex align-items-center">
                <a href="{{ route('reviews.index') }}" class="btn btn-secondary btn-sm mr-2">
                    <i class="fa fa-arrow-left"></i> @lang('app.back')
                </a>
                <h4 class="mb-0">@lang('reviewmodule::modules.review_detail')</h4>
            </div>
            @if(user()->permission('moderate_reviews') != 'none')
                <div class="d-flex">
                    @if(($review->moderation_status ?? 'pending') === 'pending')
                        <button class="btn btn-success btn-sm mr-2 review-approve" data-id="{{ $review->id }}">
                            <i class="fa fa-check mr-1"></i>@lang('reviewmodule::modules.approve')
                        </button>
                        <button class="btn btn-danger btn-sm mr-2 review-reject" data-id="{{ $review->id }}">
                            <i class="fa fa-times mr-1"></i>@lang('reviewmodule::modules.reject')
                        </button>
                    @endif
                    @if(($review->moderation_status ?? 'pending') === 'approved' && user()->permission('publish_review') != 'none')
                        <button class="btn btn-primary btn-sm review-publish" data-id="{{ $review->id }}">
                            <i class="fa fa-globe mr-1"></i>@lang('reviewmodule::modules.publish')
                        </button>
                    @endif
                </div>
            @endif
        </div>

        <div class="row mt-3">
            {{-- Review Details --}}
            <div class="col-md-8">
                <div class="card shadow-sm mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">@lang('reviewmodule::modules.review_detail')</h5>
                        @php
                            $statusClass = match($review->moderation_status ?? 'pending') {
                                'published' => 'badge-success',
                                'approved'  => 'badge-info',
                                'rejected'  => 'badge-danger',
                                default     => 'badge-warning',
                            };
                        @endphp
                        <span class="badge {{ $statusClass }} p-2">
                            {{ ucfirst($review->moderation_status ?? 'pending') }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted">@lang('reviewmodule::modules.customer')</div>
                            <div class="col-sm-8">{{ $review->customer?->name ?? '—' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted">@lang('reviewmodule::modules.service')</div>
                            <div class="col-sm-8">{{ $review->service?->name ?? '—' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted">@lang('reviewmodule::modules.booking_ref')</div>
                            <div class="col-sm-8">{{ $review->readable_id ?? '—' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted">@lang('reviewmodule::modules.date')</div>
                            <div class="col-sm-8">{{ $review->created_at?->format(company()->date_format ?? 'Y-m-d') }}</div>
                        </div>

                        {{-- Overall rating --}}
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted">@lang('reviewmodule::modules.overall_rating')</div>
                            <div class="col-sm-8">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="f-18 {{ $i <= $review->review_rating ? 'text-warning' : 'text-muted' }}">★</span>
                                @endfor
                                <span class="ml-1 text-muted">({{ $review->review_rating }}/5)</span>
                            </div>
                        </div>

                        {{-- Category ratings --}}
                        @if($review->rating_punctuality || $review->rating_quality || $review->rating_value || $review->rating_communication)
                            <div class="row mb-3">
                                <div class="col-sm-4 text-muted">@lang('reviewmodule::modules.category_ratings')</div>
                                <div class="col-sm-8">
                                    <table class="table table-sm table-borderless mb-0">
                                        @if($review->rating_punctuality)
                                            <tr>
                                                <td class="pl-0 text-muted">@lang('reviewmodule::modules.punctuality')</td>
                                                <td>
                                                    @for($i=1;$i<=5;$i++)
                                                        <span class="{{ $i<=$review->rating_punctuality?'text-warning':'text-muted' }}">★</span>
                                                    @endfor
                                                </td>
                                            </tr>
                                        @endif
                                        @if($review->rating_quality)
                                            <tr>
                                                <td class="pl-0 text-muted">@lang('reviewmodule::modules.quality')</td>
                                                <td>
                                                    @for($i=1;$i<=5;$i++)
                                                        <span class="{{ $i<=$review->rating_quality?'text-warning':'text-muted' }}">★</span>
                                                    @endfor
                                                </td>
                                            </tr>
                                        @endif
                                        @if($review->rating_value)
                                            <tr>
                                                <td class="pl-0 text-muted">@lang('reviewmodule::modules.value')</td>
                                                <td>
                                                    @for($i=1;$i<=5;$i++)
                                                        <span class="{{ $i<=$review->rating_value?'text-warning':'text-muted' }}">★</span>
                                                    @endfor
                                                </td>
                                            </tr>
                                        @endif
                                        @if($review->rating_communication)
                                            <tr>
                                                <td class="pl-0 text-muted">@lang('reviewmodule::modules.communication')</td>
                                                <td>
                                                    @for($i=1;$i<=5;$i++)
                                                        <span class="{{ $i<=$review->rating_communication?'text-warning':'text-muted' }}">★</span>
                                                    @endfor
                                                </td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        @endif

                        {{-- Comment --}}
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted">@lang('reviewmodule::modules.comment')</div>
                            <div class="col-sm-8">
                                <p class="mb-0">{{ $review->review_comment ?: '—' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Business Response --}}
                @if(user()->permission('respond_to_reviews') != 'none')
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0">@lang('reviewmodule::modules.business_response')</h5>
                        </div>
                        <div class="card-body">
                            @if($review->reviewReply)
                                <div class="alert alert-light border mb-3">
                                    <p class="mb-1">{{ $review->reviewReply->reply }}</p>
                                    <small class="text-muted">
                                        — {{ $review->reviewReply->user?->name ?? __('app.admin') }},
                                        {{ $review->reviewReply->created_at?->format(company()->date_format ?? 'Y-m-d') }}
                                    </small>
                                </div>
                            @endif

                            <form id="respond-form">
                                @csrf
                                <div class="form-group">
                                    <label>@lang('reviewmodule::modules.your_response')</label>
                                    <textarea class="form-control" id="reply-text" name="reply" rows="4"
                                        placeholder="@lang('reviewmodule::modules.write_response')">{{ $review->reviewReply?->reply }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-reply mr-1"></i>@lang('reviewmodule::modules.save_response')
                                </button>
                            </form>
                        </div>
                    </div>
                @elseif($review->reviewReply)
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0">@lang('reviewmodule::modules.business_response')</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-light border">
                                <p class="mb-1">{{ $review->reviewReply->reply }}</p>
                                <small class="text-muted">
                                    — {{ $review->reviewReply->user?->name ?? __('app.admin') }},
                                    {{ $review->reviewReply->created_at?->format(company()->date_format ?? 'Y-m-d') }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar Info --}}
            <div class="col-md-4">
                <div class="card shadow-sm mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">@lang('reviewmodule::modules.review_info')</h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">@lang('reviewmodule::modules.ref')</span>
                                <span>{{ $review->readable_id ?? '—' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">@lang('reviewmodule::modules.submitted')</span>
                                <span>{{ $review->submitted_at?->format(company()->date_format ?? 'Y-m-d') ?? '—' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">@lang('reviewmodule::modules.request_sent')</span>
                                <span>{{ $review->request_sent_at?->format(company()->date_format ?? 'Y-m-d') ?? '—' }}</span>
                            </li>
                            @if($review->provider)
                                <li class="list-group-item d-flex justify-content-between">
                                    <span class="text-muted">@lang('reviewmodule::modules.provider')</span>
                                    <span>{{ $review->provider->user?->name ?? '—' }}</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#respond-form').on('submit', function (e) {
            e.preventDefault();
            const id = '{{ $review->id }}';
            $.ajax({
                url: "{{ url('account/reviews') }}/" + id + "/respond",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    reply: $('#reply-text').val(),
                },
                success: function (res) {
                    if (res.status === 'success') {
                        toastr.success(res.message);
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        Object.values(errors).forEach(function (e) { toastr.error(e[0]); });
                    }
                }
            });
        });

        $('.review-approve').on('click', function () {
            const id = $(this).data('id');
            $.post("{{ url('account/reviews') }}/" + id + "/approve", {_token: '{{ csrf_token() }}'})
                .done(function () { location.reload(); });
        });

        $('.review-reject').on('click', function () {
            const id = $(this).data('id');
            $.post("{{ url('account/reviews') }}/" + id + "/reject", {_token: '{{ csrf_token() }}'})
                .done(function () { location.reload(); });
        });

        $('.review-publish').on('click', function () {
            const id = $(this).data('id');
            $.post("{{ url('account/reviews') }}/" + id + "/publish", {_token: '{{ csrf_token() }}'})
                .done(function () { location.reload(); });
        });
    </script>
@endpush
