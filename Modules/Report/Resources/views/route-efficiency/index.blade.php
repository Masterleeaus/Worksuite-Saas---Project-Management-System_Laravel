@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center action-bar">
        <h4 class="mb-0 f-18">Route Efficiency Report</h4>
        <a href="{{ route('report.fsm.route-efficiency.export') }}" id="export-btn" class="btn btn-secondary">
            <i class="material-icons f-16 mr-1">cloud_download</i> Export CSV
        </a>
    </div>

    {{-- Filters --}}
    <div class="row mt-3 mb-3">
        <div class="col-md-4">
            <input type="text" id="datatableRange" class="form-control"
                   placeholder="@lang('placeholders.dateRange')">
        </div>
        <div class="col-md-3">
            <select class="form-control select-picker" id="filter_worker" data-live-search="true">
                <option value="all">All Workers</option>
                @foreach ($employees ?? [] as $emp)
                    <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary" id="apply-filter">
                <i class="material-icons mr-1">search</i> Apply
            </button>
        </div>
    </div>

    @if (!$fsmInstalled)
        <div class="alert alert-warning">
            <i class="material-icons mr-2 align-middle">info</i>
            The <strong>FSMCore</strong> module or <strong>FSMRoute</strong> order tracking is not active.
            Route efficiency requires job start/end times recorded on FSM orders.
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Worker</th>
                                <th>Date</th>
                                <th class="text-right">Jobs Completed</th>
                                <th class="text-right">Billable Mins</th>
                                <th class="text-right">Avg Job Duration</th>
                                <th class="text-right">Efficiency %</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rows as $row)
                                <tr>
                                    <td>{{ $row->worker_name }}</td>
                                    <td>{{ $row->work_date }}</td>
                                    <td class="text-right">{{ $row->jobs_completed }}</td>
                                    <td class="text-right">{{ $row->billable_mins }}</td>
                                    <td class="text-right">{{ $row->avg_duration_mins }} min</td>
                                    <td class="text-right">
                                        <span class="badge badge-{{ $row->efficiency_pct >= 70 ? 'success' : ($row->efficiency_pct >= 40 ? 'warning' : 'danger') }}">
                                            {{ $row->efficiency_pct }}%
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        No route data for the selected period.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    $('#apply-filter').on('click', function () {
        const range = $('#datatableRange').val().split(' - ');
        const params = {
            startDate: range[0] ?? '',
            endDate:   range[1] ?? '',
            worker_id: $('#filter_worker').val(),
        };
        window.location.href = '{{ route("report.fsm.route-efficiency") }}?' + $.param(params);
    });
</script>
@endpush
