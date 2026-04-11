<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Reviews</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #fff; color: #333; font-size: 14px; }
        .widget-header { padding: 16px 20px; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 16px; }
        .overall-score { font-size: 36px; font-weight: bold; color: #1a73e8; line-height: 1; }
        .overall-stars { color: #ffc107; font-size: 20px; }
        .overall-label { font-size: 12px; color: #666; }
        .review-list { max-height: 600px; overflow-y: auto; }
        .review-item { padding: 16px 20px; border-bottom: 1px solid #f0f0f0; }
        .review-item:last-child { border-bottom: none; }
        .review-stars { color: #ffc107; font-size: 16px; }
        .review-stars .empty { color: #ddd; }
        .reviewer-name { font-weight: 600; font-size: 13px; }
        .review-date { font-size: 11px; color: #999; margin-left: 6px; }
        .review-comment { margin-top: 6px; line-height: 1.5; color: #444; }
        .owner-reply { background: #f8f9fa; border-left: 3px solid #1a73e8; padding: 8px 12px; margin-top: 10px; font-size: 12px; color: #555; }
        .owner-reply-label { font-weight: 600; color: #1a73e8; margin-bottom: 4px; }
        .widget-footer { padding: 12px 20px; text-align: center; font-size: 11px; color: #999; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="widget-header">
        <div>
            <div class="overall-score">{{ number_format($avgRating, 1) }}</div>
        </div>
        <div>
            <div class="overall-stars">
                @for($i = 1; $i <= 5; $i++)
                    <span class="{{ $i <= round($avgRating) ? '' : 'empty' }}">★</span>
                @endfor
            </div>
            <div class="overall-label">Based on {{ $totalCount }} review{{ $totalCount != 1 ? 's' : '' }}</div>
        </div>
    </div>

    <div class="review-list">
        @forelse($reviews as $review)
            <div class="review-item">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
                    <div class="review-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= $review->review_rating ? '' : 'empty' }}">★</span>
                        @endfor
                    </div>
                    <span class="reviewer-name">
                        {{ $review->customer?->f_name ?? $review->customer?->name ?? 'Customer' }}
                    </span>
                    <span class="review-date">{{ $review->submitted_at?->format('M Y') ?? $review->created_at?->format('M Y') }}</span>
                </div>
                @if($review->review_comment)
                    <div class="review-comment">{{ $review->review_comment }}</div>
                @endif
                @if($review->reviewReply)
                    <div class="owner-reply">
                        <div class="owner-reply-label">Response from the business</div>
                        <div>{{ $review->reviewReply->reply }}</div>
                    </div>
                @endif
            </div>
        @empty
            <div style="padding:30px;text-align:center;color:#999">No reviews yet.</div>
        @endforelse
    </div>

    <div class="widget-footer">
        Powered by CleanSmartOS
    </div>
</body>
</html>
