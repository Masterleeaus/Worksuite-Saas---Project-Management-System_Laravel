<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave a Review</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
    <style>
        body { background: #f8f9fa; }
        .review-card { max-width: 600px; margin: 60px auto; }
        .star-rating { font-size: 2rem; cursor: pointer; }
        .star-rating .star { color: #ccc; }
        .star-rating .star.selected, .star-rating .star:hover { color: #ffc107; }
    </style>
</head>
<body>
    <div class="review-card">
        <div class="card shadow">
            <div class="card-body p-4">
                <h3 class="mb-1">Share Your Experience</h3>
                @if($review->service)
                    <p class="text-muted mb-4">{{ $review->service->name }}</p>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('reviews.public_store', $token) }}" method="POST">
                    @csrf

                    {{-- Overall Rating --}}
                    <div class="form-group">
                        <label><strong>Overall Rating</strong> <span class="text-danger">*</span></label>
                        <div class="star-rating" id="overall-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="star" data-value="{{ $i }}">★</span>
                            @endfor
                        </div>
                        <input type="hidden" name="review_rating" id="review_rating" value="{{ old('review_rating') }}" required>
                        @error('review_rating')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    {{-- Category Ratings --}}
                    <div class="row">
                        @foreach(['punctuality' => 'Punctuality', 'quality' => 'Quality of Clean', 'value' => 'Value for Money', 'communication' => 'Communication'] as $key => $label)
                            <div class="col-6 mb-3">
                                <label class="mb-1"><strong>{{ $label }}</strong></label>
                                <div class="star-rating category-stars" id="stars-{{ $key }}" data-key="{{ $key }}">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="star" data-value="{{ $i }}">★</span>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating_{{ $key }}" id="rating_{{ $key }}" value="{{ old('rating_' . $key) }}">
                            </div>
                        @endforeach
                    </div>

                    {{-- Comment --}}
                    <div class="form-group">
                        <label>Your Comments</label>
                        <textarea class="form-control" name="review_comment" rows="4"
                            placeholder="Tell us about your experience...">{{ old('review_comment') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        Submit Review
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        function initStars(containerId, inputId) {
            const container = $('#' + containerId);
            const input = $('#' + inputId);
            const existingVal = input.val();
            if (existingVal) {
                container.find('.star').each(function () {
                    if ($(this).data('value') <= existingVal) $(this).addClass('selected');
                });
            }
            container.find('.star').on('click', function () {
                const val = $(this).data('value');
                input.val(val);
                container.find('.star').each(function () {
                    $(this).toggleClass('selected', $(this).data('value') <= val);
                });
            });
        }

        initStars('overall-stars', 'review_rating');

        $('.category-stars').each(function () {
            const key = $(this).data('key');
            initStars('stars-' + key, 'rating_' + key);
        });
    </script>
</body>
</html>
