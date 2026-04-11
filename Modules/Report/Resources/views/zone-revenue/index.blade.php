@extends('layouts.app')

@push('datatable-styles')
    <script src="{{ asset('vendor/jquery/Chart.min.js') }}"></script>
@endpush

@section('content')
<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center action-bar">
        <h4 class="mb-0 f-18">Revenue by Zone</h4>
        <a href="{{ route('report.fsm.zone-revenue.export') }}" id="export-btn" class="btn btn-secondary">
            <i class="material-icons f-16 mr-1">cloud_download</i> Export CSV
        </a>
    </div>

    {{-- Filters --}}
    <div class="row mt-3 mb-3">
        <div class="col-md-4">
            <input type="text" id="datatableRange" class="form-control"
                   placeholder="@lang('placeholders.dateRange')">
        </div>
        @if(($territories ?? collect())->isNotEmpty())
        <div class="col-md-3">
            <select class="form-control select-picker" id="filter_territory" data-live-search="true">
                <option value="all">All Territories</option>
                @foreach ($territories as $territory)
                    <option value="{{ $territory->id }}">{{ $territory->name }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="col-md-2">
            <button class="btn btn-primary" id="apply-filter">
                <i class="material-icons mr-1">search</i> Apply
            </button>
        </div>
    </div>

    {{-- Bar Chart --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-header">
            <h5 class="mb-0">Revenue by Zone</h5>
        </div>
        <div class="card-body">
            <canvas id="zoneRevenueChart" height="80"></canvas>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Zone / Suburb</th>
                            <th class="text-right">Jobs</th>
                            <th class="text-right">Total Revenue</th>
                            <th class="text-right">Avg per Job</th>
                            <th class="text-right">Cost per Job</th>
                        </tr>
                    </thead>
                    <tbody id="zone-table-body">
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Click <strong>Apply</strong> to load data.
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
    let zoneChart = null;

    const buildParams = () => {
        const range = $('#datatableRange').val().split(' - ');
        return {
            startDate:    range[0] ?? '',
            endDate:      range[1] ?? '',
            territory_id: $('#filter_territory').val() ?? 'all',
        };
    };

    const loadZoneRevenue = () => {
        $.get('{{ route("report.fsm.zone-revenue.chart") }}', buildParams(), function(data) {
            // Chart
            if (zoneChart) zoneChart.destroy();
            const ctx = document.getElementById('zoneRevenueChart').getContext('2d');
            zoneChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.zones,
                    datasets: [{
                        label: 'Revenue',
                        data: data.totals,
                        backgroundColor: 'rgba(54,162,235,0.7)',
                    }]
                },
                options: { responsive: true, scales: { y: { beginAtZero: true } } }
            });

            // Table
            if (!data.rows || data.rows.length === 0) {
                $('#zone-table-body').html('<tr><td colspan="5" class="text-center text-muted py-4">No data for selected period.</td></tr>');
                return;
            }
            // Escape helper to prevent XSS when inserting server data into the DOM.
            const esc = (v) => String(v ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
            const rows = data.rows.map(r => `<tr>
                <td>${esc(r.zone_name)}</td>
                <td class="text-right">${esc(r.job_count)}</td>
                <td class="text-right">${esc(r.total_revenue)}</td>
                <td class="text-right">${esc(r.avg_revenue)}</td>
                <td class="text-right">${esc(r.cost_per_job)}</td>
            </tr>`);
            $('#zone-table-body').html(rows.join(''));
        }, 'json');

        // Update export link
        $('#export-btn').attr('href', '{{ route("report.fsm.zone-revenue.export") }}?' + $.param(buildParams()));
    };

    $('#apply-filter').on('click', loadZoneRevenue);
</script>
@endpush
