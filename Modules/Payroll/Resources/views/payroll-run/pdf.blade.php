<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payroll Run #{{ $run->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        h1 { font-size: 18px; }
        h2 { font-size: 14px; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ccc; padding: 4px 6px; }
        th { background: #f5f5f5; }
        .badge-danger { background: #dc3545; color: #fff; padding: 1px 4px; border-radius: 3px; }
        .badge-warning { background: #ffc107; color: #000; padding: 1px 4px; border-radius: 3px; }
        .total-row { font-weight: bold; background: #f9f9f9; }
    </style>
</head>
<body>
    <h1>Payroll Run #{{ $run->id }}</h1>
    <p>
        Period: {{ $run->period_start->format('d/m/Y') }} – {{ $run->period_end->format('d/m/Y') }}<br>
        State: {{ $run->state ?? 'All States' }}<br>
        Status: {{ strtoupper($run->status) }}<br>
        Approved by: {{ optional($run->approver)->name ?? '—' }} on {{ $run->approved_at ? $run->approved_at->format('d/m/Y H:i') : '—' }}<br>
        Generated: {{ now()->format('d/m/Y H:i') }}
    </p>

    @foreach($byEmployee as $empId => $group)
    <h2>{{ optional($group['user'])->name }}</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Hours</th>
                <th>Rate Type</th>
                <th>Rate ($/hr)</th>
                <th>Gross Pay</th>
                <th>Rooms</th>
                <th>Commission</th>
                <th>Total Pay</th>
                <th>Flags</th>
            </tr>
        </thead>
        <tbody>
            @foreach($group['items'] as $item)
            <tr @if($item->is_overridden) style="background:#fff3cd" @endif>
                <td>{{ \Carbon\Carbon::parse($item->job_date)->format('d/m/Y') }}</td>
                <td>{{ number_format($item->hours_worked, 2) }}</td>
                <td>{{ $item->rate_type }}</td>
                <td>${{ number_format($item->rate_applied, 4) }}</td>
                <td>${{ number_format($item->gross_pay, 2) }}</td>
                <td>{{ $item->rooms_cleaned }}</td>
                <td>${{ number_format($item->commission_amount, 2) }}</td>
                <td>${{ number_format($item->total_pay, 2) }}</td>
                <td>
                    @if($item->is_public_holiday) PH @endif
                    @if($item->is_overridden) OVR @endif
                </td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td>TOTAL</td>
                <td>{{ number_format($group['total_hours'], 2) }}</td>
                <td colspan="2"></td>
                <td>${{ number_format($group['items']->sum('gross_pay'), 2) }}</td>
                <td>{{ $group['items']->sum('rooms_cleaned') }}</td>
                <td>${{ number_format($group['items']->sum('commission_amount'), 2) }}</td>
                <td>${{ number_format($group['total_pay'], 2) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    @endforeach

    <h2>Grand Summary</h2>
    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Total Hours</th>
                <th>Total Pay</th>
            </tr>
        </thead>
        <tbody>
            @foreach($byEmployee as $group)
            <tr>
                <td>{{ optional($group['user'])->name }}</td>
                <td>{{ number_format($group['total_hours'], 2) }}</td>
                <td>${{ number_format($group['total_pay'], 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td>GRAND TOTAL</td>
                <td>{{ number_format(collect($byEmployee)->sum('total_hours'), 2) }}</td>
                <td>${{ number_format(collect($byEmployee)->sum('total_pay'), 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
