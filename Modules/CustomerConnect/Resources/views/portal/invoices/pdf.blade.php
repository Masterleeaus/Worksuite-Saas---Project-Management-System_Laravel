{{-- Minimal invoice PDF template for fallback rendering --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number ?? ('#' . $invoice->id) }}</title>
    <style>
        body { font-family: sans-serif; font-size: 13px; color: #333; }
        .header { border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .invoice-meta td { padding: 3px 8px; }
        .items th, .items td { padding: 6px 10px; border: 1px solid #ddd; }
        .items th { background: #f5f5f5; }
        .total { text-align: right; font-size: 15px; font-weight: bold; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Invoice {{ $invoice->invoice_number ?? ('#' . $invoice->id) }}</h2>
    </div>
    <table class="invoice-meta">
        <tr>
            <td><strong>Issue Date:</strong></td>
            <td>{{ $invoice->issue_date ? \Carbon\Carbon::parse($invoice->issue_date)->format('d M Y') : '—' }}</td>
        </tr>
        <tr>
            <td><strong>Due Date:</strong></td>
            <td>{{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') : '—' }}</td>
        </tr>
        <tr>
            <td><strong>Status:</strong></td>
            <td>{{ ucfirst($invoice->status ?? 'unpaid') }}</td>
        </tr>
    </table>
    <br>
    <div class="total">
        Total: {{ $invoice->currency_symbol ?? '$' }}{{ number_format($invoice->total ?? 0, 2) }}
    </div>
</body>
</html>
