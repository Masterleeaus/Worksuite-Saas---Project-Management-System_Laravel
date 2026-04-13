<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Proof of Delivery — {{ $order->name }}</title>
</head>
<body style="font-family: Arial, sans-serif; font-size: 14px; color: #1a1a1a; line-height: 1.6;">

<p>Dear Customer,</p>

<p>
    Please find attached the <strong>Proof of Delivery</strong> for visit
    <strong>#{{ $order->id }} — {{ $order->name }}</strong>,
    completed on {{ $order->date_end?->format('d M Y') ?? now()->format('d M Y') }}.
</p>

<p>The attached PDF includes:</p>
<ul>
    <li>Visit summary (location, technician, dates)</li>
    <li>Completed checklist steps with notes</li>
    <li>Photo and signature evidence where captured</li>
</ul>

<p>If you have any questions, please don't hesitate to contact us.</p>

<p>
    Kind regards,<br>
    <strong>{{ config('app.name') }}</strong>
</p>

<hr style="border: none; border-top: 1px solid #e5e7eb; margin: 20px 0;">
<small style="color: #9ca3af;">
    This email was sent by Titan Go — Worksuite Field Service Management.
    Visit #{{ $order->id }}.
</small>

</body>
</html>
