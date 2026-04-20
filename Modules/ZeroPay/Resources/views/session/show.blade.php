<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZeroPay Session</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8fafc; color: #111827; margin: 0; padding: 24px; }
        .card { max-width: 880px; margin: 0 auto; background: #fff; border-radius: 12px; border: 1px solid #e5e7eb; padding: 20px; }
        .muted { color: #6b7280; }
        .row { display: flex; gap: 12px; flex-wrap: wrap; }
        .pill { padding: 6px 10px; border-radius: 999px; background: #eef2ff; color: #3730a3; font-size: 12px; }
        .btn { border: 0; background: #111827; color: #fff; padding: 10px 14px; border-radius: 8px; cursor: pointer; }
        select { padding: 10px; border-radius: 8px; border: 1px solid #d1d5db; min-width: 220px; }
        .mt { margin-top: 14px; }
        a { color: #1d4ed8; text-decoration: none; }
    </style>
</head>
<body>
<div class="card">
    <h2>ZeroPay Payment Session</h2>
    <p class="muted">
        Invoice #{{ $session->invoice?->invoice_number }}
        <span aria-hidden="true"> · </span><span class="sr-only">, </span>
        Session {{ $session->public_token }}
    </p>

    @if (session('status'))
        <p class="pill">{{ session('status') }}</p>
    @endif

    <div class="row mt">
        <div><strong>Amount:</strong> {{ number_format((float) $session->amount, 2) }}</div>
        <div><strong>Status:</strong> {{ $session->status }}</div>
        <div><strong>Selected Method:</strong> {{ $session->selected_method ?? 'Not selected' }}</div>
    </div>


    <div class="mt"><strong>Recommended Rail:</strong> {{ $recommended_rail ?? 'N/A' }}</div>
    <div class="mt">
        <strong>QR / Link</strong>
        <div><a href="{{ $session->payment_link }}" target="_blank" rel="noopener">{{ $session->payment_link }}</a></div>
    </div>

    <form method="POST" action="{{ route('zeropay.session.select-method', $session->public_token) }}" class="mt">
        @csrf
        <label for="method"><strong>Choose Payment Method</strong></label><br>
        <select name="method" id="method" required>
            <option value="" disabled selected>Select a method</option>
            @foreach ($methods as $method)
                <option value="{{ $method }}">{{ str_replace('_', ' ', ucfirst($method)) }}</option>
            @endforeach
        </select>
        <button class="btn" type="submit">Select Method</button>
    </form>

    <form method="POST" action="{{ route('zeropay.session.email-invoice', $session->public_token) }}" class="mt">
        @csrf
        <button class="btn" type="submit">Email Invoice</button>
    </form>

    <div class="mt">
        @php
            $invoiceToken = $session->downloadTokens->firstWhere('type', 'invoice');
            $receiptToken = $session->downloadTokens->firstWhere('type', 'receipt');
        @endphp

        @if ($invoiceToken)
            <a href="{{ route('zeropay.download.invoice', $invoiceToken->token) }}">Download Invoice</a>
        @endif
        @if ($invoiceToken && $receiptToken)
            &nbsp;|&nbsp;
        @endif
        @if ($receiptToken)
            <a href="{{ route('zeropay.download.receipt', $receiptToken->token) }}">Download Receipt</a>
        @endif
    </div>
</div>
</body>
</html>
