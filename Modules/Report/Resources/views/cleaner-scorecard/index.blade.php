@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center action-bar">
        <h4 class="mb-0 f-18">Cleaner Scorecard</h4>
        <a href="{{ route('report.fsm.cleaner-scorecard.export') }}" class="btn btn-secondary">
            <i class="material-icons f-16 mr-1">cloud_download</i> Export CSV
        </a>
    </div>

    {{-- Date filters --}}
    <div class="row mt-3 mb-3">
        <div class="col-md-4">
            <input type="text" id="datatableRange" class="form-control"
                   placeholder="@lang('placeholders.dateRange')">
        </div>
        <div class="col-md-3">
            <select class="form-control select-picker" id="filter_cleaner" data-live-search="true">
                <option value="all">All Cleaners</option>
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

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="scorecard-table">
                    <thead class="bg-light">
                        <tr>
                            <th>Cleaner</th>
                            <th class="text-center">Jobs Completed</th>
                            <th class="text-center">Cancelled</th>
                            <th class="text-center">Recleans</th>
                            <th class="text-center">Avg Rating</th>
                            <th class="text-center">Punctuality %</th>
                            <th class="text-center">Complaints</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="scorecard-body">
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                Click <strong>Apply</strong> to load scorecards.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const renderRow = (row) => {
        const rating = row.avg_rating ? `<span class="text-warning">${row.avg_rating} ★</span>` : '—';
        const punc   = row.punctuality_pct !== null ? row.punctuality_pct + '%' : '—';
        const badge  = row.complaints > 0
            ? `<span class="badge badge-danger">${row.complaints}</span>`
            : '<span class="badge badge-success">0</span>';

        return `<tr>
            <td>${row.cleaner_name}</td>
            <td class="text-center">${row.completed}</td>
            <td class="text-center">${row.cancelled}</td>
            <td class="text-center">${row.recleans}</td>
            <td class="text-center">${rating}</td>
            <td class="text-center">${punc}</td>
            <td class="text-center">${badge}</td>
            <td>
                <a href="/account/reports/fsm/cleaner-scorecard/${row.cleaner_id}" class="btn btn-sm btn-outline-primary">View</a>
            </td>
        </tr>`;
    };

    const buildParams = () => {
        const range = $('#datatableRange').val().split(' - ');
        return { startDate: range[0] ?? '', endDate: range[1] ?? '', cleaner_id: $('#filter_cleaner').val() };
    };

    const loadScorecards = () => {
        $.get('/account/reports/fsm/cleaner-scorecard', buildParams(), function(data) {
            if (!Array.isArray(data) || data.length === 0) {
                $('#scorecard-body').html('<tr><td colspan="8" class="text-center text-muted py-4">No data for selected period.</td></tr>');
                return;
            }
            $('#scorecard-body').html(data.map(renderRow).join(''));
        }, 'json');
    };

    $('#apply-filter').on('click', loadScorecards);
</script>
@endpush
