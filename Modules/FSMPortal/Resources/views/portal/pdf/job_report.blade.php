<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Job Report – {{ $order->name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1 { font-size: 20px; margin-bottom: 4px; }
        h2 { font-size: 15px; border-bottom: 1px solid #dee2e6; padding-bottom: 4px; margin-top: 20px; margin-bottom: 10px; }
        .header-section { border-bottom: 2px solid #0d6efd; padding-bottom: 10px; margin-bottom: 16px; }
        .label { color: #6c757d; font-size: 11px; }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            color: #fff;
            font-size: 11px;
        }
        table { width: 100%; border-collapse: collapse; }
        table.dl td { padding: 4px 6px; }
        table.dl td:first-child { width: 40%; color: #6c757d; }
        .photo-grid { }
        .photo-grid img { width: 140px; height: 140px; object-fit: cover; margin: 4px; border: 1px solid #dee2e6; border-radius: 4px; }
        .footer { margin-top: 30px; border-top: 1px solid #dee2e6; padding-top: 8px; color: #6c757d; font-size: 10px; }
    </style>
</head>
<body>

    <div class="header-section">
        <h1>Job Completion Report</h1>
        <div class="label">Reference: <strong>{{ $order->name }}</strong></div>
        <div class="label">Generated: {{ now()->format('d M Y H:i') }}</div>
    </div>

    {{-- Job details --}}
    <h2>Job Details</h2>
    <table class="dl">
        <tr>
            <td>Location / Site</td>
            <td>
                {{ $order->location?->name ?? '—' }}
                @if($order->location?->street)
                    – {{ implode(', ', array_filter([
                        $order->location->street,
                        $order->location->city,
                        $order->location->state,
                        $order->location->zip,
                    ])) }}
                @endif
            </td>
        </tr>
        <tr>
            <td>Assigned Worker</td>
            <td>{{ $order->person?->name ?? '—' }}</td>
        </tr>
        <tr>
            <td>Current Stage</td>
            <td>
                @if($order->stage)
                    <span class="badge" style="background:{{ $order->stage->color ?? '#6c757d' }};">{{ $order->stage->name }}</span>
                @else
                    —
                @endif
            </td>
        </tr>
        <tr>
            <td>Scheduled Start</td>
            <td>{{ $order->scheduled_date_start?->format('d M Y H:i') ?? '—' }}</td>
        </tr>
        <tr>
            <td>Scheduled End</td>
            <td>{{ $order->scheduled_date_end?->format('d M Y H:i') ?? '—' }}</td>
        </tr>
        <tr>
            <td>Actual Check-in</td>
            <td>{{ $order->date_start?->format('d M Y H:i') ?? '—' }}</td>
        </tr>
        <tr>
            <td>Actual Completion</td>
            <td>{{ $order->date_end?->format('d M Y H:i') ?? '—' }}</td>
        </tr>
        @if($order->description)
        <tr>
            <td>Notes</td>
            <td>{{ $order->description }}</td>
        </tr>
        @endif
    </table>

    {{-- Evidence photos --}}
    @if($evidencePhotos->isNotEmpty())
        <h2>Completion Photos</h2>
        <div class="photo-grid">
            @foreach($evidencePhotos as $photo)
                @php $url = $photo->photo_url; @endphp
                @if($url)
                    <img src="{{ $url }}" alt="{{ $photo->original_filename }}">
                @endif
            @endforeach
        </div>
    @endif

    <div class="footer">
        This report was generated automatically by CleanSmartOS. Reference: {{ $order->name }}.
    </div>

</body>
</html>
