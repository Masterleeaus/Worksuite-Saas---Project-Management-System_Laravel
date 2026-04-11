<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How was your recent clean?</title>
    <style>
        body { margin: 0; padding: 0; background: #f4f4f4; font-family: Arial, sans-serif; }
        .wrapper { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .header { background: #1a73e8; padding: 30px 40px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 24px; }
        .body { padding: 32px 40px; color: #333; font-size: 15px; line-height: 1.6; }
        .stars { font-size: 36px; text-align: center; margin: 20px 0; color: #ccc; }
        .cta { text-align: center; margin: 28px 0; }
        .cta a { background: #1a73e8; color: #fff; text-decoration: none; padding: 14px 32px; border-radius: 6px; font-size: 16px; font-weight: bold; }
        .footer { padding: 20px 40px; text-align: center; font-size: 12px; color: #888; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>How was your recent clean?</h1>
        </div>
        <div class="body">
            <p>Hi {{ $customer->f_name ?? $customer->name ?? 'there' }},</p>
            <p>Thank you for choosing us! We hope you're delighted with your recent cleaning service.</p>
            <p>Your feedback helps us improve and helps other customers find a cleaner they can trust.</p>
            <div class="stars">★ ★ ★ ★ ★</div>
            <div class="cta">
                <a href="{{ $reviewUrl }}">Leave Your Review</a>
            </div>
            <p style="font-size:13px;color:#666;text-align:center;">This link is unique to you and will only work once.</p>
        </div>
        <div class="footer">
            <p>You received this email because you recently used our cleaning service.<br>
            If you have any questions, please reply to this email.</p>
        </div>
    </div>
</body>
</html>
