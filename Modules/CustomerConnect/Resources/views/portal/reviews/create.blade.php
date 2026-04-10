@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">Leave a Review</h2>
                    <p class="text-muted mb-0">Share your experience to help us improve our service.</p>
                </div>
                @if(Route::has('customerconnect.portal.bookings.index'))
                    <a href="{{ route('customerconnect.portal.bookings.index') }}" class="btn btn-outline-secondary">
                        <i class="fa fa-arrow-left me-1"></i> Back
                    </a>
                @endif
            </div>

            @if($booking)
                <div class="alert alert-info d-flex align-items-center py-2 mb-4">
                    <i class="fa fa-info-circle me-2"></i>
                    <span>
                        Reviewing: <strong>{{ $booking->heading ?? 'Cleaning Service' }}</strong>
                        on {{ $booking->start_date_time ? \Carbon\Carbon::parse($booking->start_date_time)->format('d M Y') : '' }}
                    </span>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('customerconnect.portal.reviews.store') }}" method="POST">
                        @csrf

                        @if($booking)
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                        @endif

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Your Rating <span class="text-danger">*</span></label>
                            <div class="d-flex gap-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="rating" id="rating_{{ $i }}" value="{{ $i }}"
                                               {{ old('rating') == $i ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="rating_{{ $i }}">
                                            {{ str_repeat('★', $i) }}{{ str_repeat('☆', 5 - $i) }}
                                        </label>
                                    </div>
                                @endfor
                            </div>
                            @error('rating')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="review" class="form-label fw-semibold">Your Review <span class="text-muted fw-normal">(optional)</span></label>
                            <textarea name="review" id="review" rows="5"
                                      class="form-control @error('review') is-invalid @enderror"
                                      placeholder="Tell us about your experience…">{{ old('review') }}</textarea>
                            @error('review')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-star me-1"></i> Submit Review
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
