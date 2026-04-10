<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate your recent clean</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; color: #212529; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .header { background: #ffc107; padding: 32px 32px 24px; text-align: center; }
        .header h1 { margin: 0; font-size: 26px; color: #212529; }
        .body { padding: 32px; }
        .job-card { background: #f8f9fa; border-radius: 6px; padding: 16px; margin-bottom: 24px; }
        .job-card dt { font-size: 12px; color: #6c757d; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 2px; }
        .job-card dd { font-size: 16px; font-weight: 600; margin: 0 0 12px; }
        .stars { font-size: 36px; letter-spacing: 4px; display: block; text-align: center; margin: 16px 0; color: #ffc107; }
        .cta { display: block; text-align: center; background: #ffc107; color: #212529; text-decoration: none; font-weight: 700; font-size: 18px; padding: 16px 32px; border-radius: 6px; margin: 24px 0; }
        .footer { text-align: center; font-size: 12px; color: #6c757d; padding: 16px 32px 24px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⭐ How did we do?</h1>
            <p style="margin:8px 0 0; font-size:16px;">
                Hi {{ $client->name ?? 'there' }}, your clean is complete — we'd love your feedback!
            </p>
        </div>
        <div class="body">
            <div class="job-card">
                <dl>
                    <dt>Job Reference</dt>
                    <dd>{{ $order->name ?? 'N/A' }}</dd>
                    @if(!empty($order->scheduled_date_start))
                        <dt>Date</dt>
                        <dd>{{ \Carbon\Carbon::parse($order->scheduled_date_start)->format('d M Y') }}</dd>
                    @endif
                    @if(!empty($order->person))
                        <dt>Cleaner</dt>
                        <dd>{{ $order->person->name ?? '—' }}</dd>
                    @endif
                </dl>
            </div>

            <p style="text-align:center; font-size:16px; margin-bottom:8px;">
                Tap the button below to leave a 1–5 star rating and let us know how your cleaner performed.
            </p>

            <span class="stars">★ ★ ★ ★ ★</span>

            <a href="{{ $ratingUrl }}" class="cta">Rate Your Clean</a>

            <p style="font-size:13px; color:#6c757d; text-align:center;">
                This link will take you securely to your client portal. No password required if you're already logged in.
            </p>
        </div>
        <div class="footer">
            You're receiving this email because you recently had a clean booked through our system.<br>
            If you have any issues, please reply to this email.
        </div>
    </div>
</body>
</html>
