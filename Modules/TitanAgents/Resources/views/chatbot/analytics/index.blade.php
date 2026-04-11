@extends('layouts/layoutMaster')

@section('title', 'Analytics — ' . $chatbot->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('titanagents.chatbot.show', $chatbot) }}" class="btn btn-outline-secondary me-3">
            <i class="ti ti-arrow-left"></i>
        </a>
        <h4 class="mb-0">Analytics — {{ $chatbot->name }}</h4>
        <div class="ms-auto d-flex gap-2">
            <form method="GET" class="d-flex gap-2">
                <select name="period" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach(['7d' => 'Last 7 days', '30d' => 'Last 30 days', '90d' => 'Last 90 days'] as $val => $label)
                        <option value="{{ $val }}" {{ $period === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </form>
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">Export</button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('titanagents.chatbot.analytics.export', [$chatbot, 'format' => 'csv']) }}">Export CSV</a></li>
                    <li><a class="dropdown-item" href="{{ route('titanagents.chatbot.analytics.export', [$chatbot, 'format' => 'json']) }}">Export JSON</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="mb-0">{{ $summary['total_conversations'] }}</h3>
                    <small class="text-muted">Total Conversations</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="mb-0 text-success">{{ $summary['resolution_rate'] }}%</h3>
                    <small class="text-muted">Resolution Rate</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="mb-0 text-warning">{{ $summary['escalation_rate'] }}%</h3>
                    <small class="text-muted">Escalation Rate</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="mb-0">{{ $summary['resolved_conversations'] }}</h3>
                    <small class="text-muted">Resolved</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="mb-0">{{ $summary['avg_messages_per_conversation'] }}</h3>
                    <small class="text-muted">Avg Messages / Conv.</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="mb-0">{{ $summary['page_visits'] }}</h3>
                    <small class="text-muted">Page Visits</small>
                </div>
            </div>
        </div>
    </div>

    @if(count($dailyData))
    <div class="card">
        <div class="card-header"><h6 class="mb-0">Conversations by Day</h6></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Date</th><th>Conversations</th></tr>
                    </thead>
                    <tbody>
                        @foreach($dailyData as $row)
                            <tr>
                                <td>{{ $row['date'] }}</td>
                                <td>{{ $row['count'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
