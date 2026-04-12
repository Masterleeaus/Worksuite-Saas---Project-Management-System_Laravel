<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Invoice #{{ $invoice->invoice_number }}</title>
    <link rel="stylesheet" href="{{ asset('vendor/css/bootstrap.min.css') }}">
    <style>
        body { background: #f0f2f5; }
        .pay-card { max-width: 520px; margin: 60px auto; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,.1); }
        .pay-header { background: linear-gradient(135deg,#6a11cb 0%,#2575fc 100%); border-radius: 12px 12px 0 0; padding: 28px 32px; color:#fff; }
        .gateway-btn { width:100%; margin-bottom:12px; padding:14px; font-size:1rem; border-radius:8px; }
    </style>
</head>
<body>

<div class="card pay-card">
    <div class="pay-header">
        <h4 class="mb-1">Payment</h4>
        <div>Invoice #{{ $invoice->invoice_number }}</div>
    </div>

    <div class="card-body p-4">

        {{-- Invoice summary --}}
        <table class="table table-sm mb-4">
            <tr>
                <th>Invoice</th>
                <td class="text-end">#{{ $invoice->invoice_number }}</td>
            </tr>
            <tr>
                <th>Amount Due</th>
                <td class="text-end fw-bold text-primary">
                    {{ $invoice->currency?->currency_symbol ?? '$' }}
                    {{ number_format($invoice->amountDue(), 2) }}
                </td>
            </tr>
            <tr>
                <th>Due Date</th>
                <td class="text-end">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</td>
            </tr>
        </table>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('titanpay.payment.process', $link->token) }}">
            @csrf

            <p class="text-muted mb-3 small">Select your preferred payment method:</p>

            {{-- Card / Stripe --}}
            <button type="submit" name="gateway" value="stripe"
                    class="btn btn-outline-primary gateway-btn">
                <i class="fa fa-credit-card me-2"></i> Pay by Card (Stripe)
            </button>

            {{-- PayPal --}}
            <button type="submit" name="gateway" value="paypal"
                    class="btn btn-outline-warning gateway-btn">
                <i class="fab fa-paypal me-2"></i> Pay with PayPal
            </button>

            {{-- Cryptomus --}}
            @if(config('titanpay.cryptomus.is_active'))
            <button type="submit" name="gateway" value="cryptomus"
                    class="btn btn-outline-dark gateway-btn">
                <i class="fa fa-bitcoin me-2"></i> Pay with Crypto (Cryptomus)
            </button>
            @endif

            {{-- Bank Transfer / Offline --}}
            <button type="submit" name="gateway" value="offline"
                    class="btn btn-outline-secondary gateway-btn">
                <i class="fa fa-university me-2"></i> Bank Transfer / Manual Payment
            </button>
        </form>

    </div>

    <div class="card-footer text-center text-muted small py-3">
        Secured by TitanPay &bull; Link expires {{ $link->expires_at?->diffForHumans() ?? 'soon' }}
    </div>
</div>

</body>
</html>
