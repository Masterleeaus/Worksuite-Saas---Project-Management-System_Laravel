@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="mb-3 d-flex align-items-center justify-content-between">
        <div>
            <h4 class="mb-1">CustomerConnect Health</h4>
            <div class="text-muted">Last 24 hours KPIs + last 7 days channel breakdown.</div>
        </div>
        <a href="{{ url('/account/customer-connect/inbox') }}" class="btn btn-sm btn-outline-secondary">Inbox</a>
    </div>

    <div class="row">
        @foreach($kpis as $k => $v)
        <div class="col-md-2 col-sm-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-uppercase text-muted small">{{ str_replace('_',' ', $k) }}</div>
                    <div class="fs-4 fw-bold">{{ is_null($v) ? '—' : $v }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-lg-6 mb-3">
            <div class="card">
                <div class="card-header">Deliveries by Channel (7 days)</div>
                <div class="card-body">
                    @if(!$byChannel)
                        <div class="text-muted">No channel data found (or column missing).</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Channel</th>
                                        <th>Total</th>
                                        <th>Sent</th>
                                        <th>Failed</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($byChannel as $row)
                                        <tr>
                                            <td>{{ $row->channel }}</td>
                                            <td>{{ $row->total }}</td>
                                            <td>{{ $row->sent }}</td>
                                            <td>{{ $row->failed }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-3">
            <div class="card">
                <div class="card-header">Recent Alerts</div>
                <div class="card-body">
                    @if(!$recentAlerts || count($recentAlerts) === 0)
                        <div class="text-muted">No alerts recorded.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>When</th>
                                        <th>Type</th>
                                        <th>Severity</th>
                                        <th>Message</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentAlerts as $a)
                                        <tr>
                                            <td class="text-muted">{{ $a->created_at }}</td>
                                            <td>{{ $a->type ?? 'alert' }}</td>
                                            <td>{{ $a->severity ?? 'info' }}</td>
                                            <td>{{ $a->message ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
