<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You!</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
    <style>
        body { background: #f8f9fa; }
        .thankyou-card { max-width: 500px; margin: 80px auto; text-align: center; }
        .star-display { font-size: 2.5rem; color: #ffc107; }
    </style>
</head>
<body>
    <div class="thankyou-card">
        <div class="card shadow p-4">
            <div class="star-display mb-3">
                @for($i = 1; $i <= 5; $i++)
                    <span>{{ $i <= $review->review_rating ? '★' : '☆' }}</span>
                @endfor
            </div>
            <h3>Thank you for your review!</h3>
            <p class="text-muted">Your feedback has been submitted and is awaiting moderation. We appreciate you taking the time to share your experience.</p>
            @if($review->review_rating >= 4)
                <div class="alert alert-success mt-3">
                    <p class="mb-2"><strong>Love your experience?</strong></p>
                    <p class="mb-2 small">Help others find us by sharing on:</p>
                    <a href="https://www.google.com/maps" target="_blank" class="btn btn-sm btn-outline-secondary mr-1">
                        Google
                    </a>
                    <a href="https://www.facebook.com" target="_blank" class="btn btn-sm btn-outline-secondary">
                        Facebook
                    </a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
