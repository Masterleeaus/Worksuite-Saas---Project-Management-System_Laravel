<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crypto Payment — Invoice #{{ $invoice->invoice_number }}</title>
    <link rel="stylesheet" href="{{ asset('vendor/css/bootstrap.min.css') }}">
    <style>
        body { background: #f0f2f5; }
        .return-card { max-width: 480px; margin: 80px auto; border-radius: 12px; text-align: center; padding: 40px; box-shadow: 0 4px 24px rgba(0,0,0,.1); }
    </style>
</head>
<body>
<div class="card return-card">
    <div class="mb-4 text-success" style="font-size:3rem"><i class="fa fa-check-circle"></i></div>
    <h4 class="mb-2">Payment Submitted</h4>
    <p class="text-muted">Your cryptocurrency payment for invoice <strong>#{{ $invoice->invoice_number }}</strong> has been submitted.</p>
    <p class="text-muted small">Blockchain confirmations can take a few minutes. You will receive an email once your payment is confirmed.</p>
</div>
</body>
</html>
