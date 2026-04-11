@extends('layouts.app')

@push('datatable-styles')
    <script src="{{ asset('vendor/jquery/Chart.min.js') }}"></script>
    @include('sections.datatable_css')
@endpush

@section('filter-section')
<x-filters.filter-box>
    {{-- Date Range --}}
    <div class="select-box d-flex pr-2 border-right-grey border-right-grey-sm-0">
        <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">@lang('app.duration')</p>
        <div class="select-status d-flex">
            <input type="text"
                   class="position-relative text-dark form-control border-0 p-2 text-left f-14 f-w-500 border-additional-grey"
                   id="datatableRange" placeholder="@lang('placeholders.dateRange')">
        </div>
    </div>

    {{-- Service Type --}}
    <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0">
        <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">Service Type</p>
        <div class="select-status">
            <select class="form-control select-picker" id="service_type">
                <option value="all">All</option>
                @foreach ($serviceTypes ?? [] as $type)
                    <option value="{{ $type }}">{{ ucwords(str_replace('_', ' ', $type)) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Cleaner --}}
    <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0">
        <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">Cleaner</p>
        <div class="select-status">
            <select class="form-control select-picker" id="cleaner_id" data-live-search="true">
                <option value="all">All Cleaners</option>
                @foreach ($employees ?? [] as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Zone / Territory --}}
    @if(($territories ?? collect())->isNotEmpty())
    <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0">
        <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">Zone</p>
        <div class="select-status">
            <select class="form-control select-picker" id="territory_id" data-live-search="true">
                <option value="all">All Zones</option>
                @foreach ($territories as $territory)
                    <option value="{{ $territory->id }}">{{ $territory->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    @endif

    {{-- Status --}}
    <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0">
        <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">Status</p>
        <div class="select-status">
            <select class="form-control select-picker" id="booking_status">
                <option value="all">All Statuses</option>
                @foreach ($statuses ?? [] as $status)
                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <x-filters.filter-actions>
        <x-slot name="search">
            <button type="button" class="btn-search btn btn-outline-secondary mr-1" id="refresh-report">
                <i class="material-icons mr-1">search</i> Apply
            </button>
        </x-slot>
        <x-slot name="reset">
            <button type="button" class="btn-reset btn btn-outline-secondary" id="reset-report">
                <i class="material-icons mr-1">rotate_left</i> Reset
            </button>
        </x-slot>
    </x-filters.filter-actions>
</x-filters.filter-box>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center action-bar">
        <h4 class="mb-0 f-18">Booking Performance Report</h4>
        <a href="{{ route('report.fsm.bookings.export') }}" id="export-btn" class="btn btn-secondary">
            <i class="material-icons f-16 mr-1">cloud_download</i> Export CSV
        </a>
    </div>

    {{-- KPI Cards --}}
    <div class="row mt-4" id="kpi-cards">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <p class="text-muted f-12 mb-1">Total Bookings</p>
                    <h3 class="f-w-600" id="kpi-total">—</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100 border-left-success">
                <div class="card-body text-center">
                    <p class="text-muted f-12 mb-1">Completion Rate</p>
                    <h3 class="f-w-600 text-success" id="kpi-completion">—</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100 border-left-danger">
                <div class="card-body text-center">
                    <p class="text-muted f-12 mb-1">Cancellation Rate</p>
                    <h3 class="f-w-600 text-danger" id="kpi-cancellation">—</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100 border-left-warning">
                <div class="card-body text-center">
                    <p class="text-muted f-12 mb-1">Reclean Rate</p>
                    <h3 class="f-w-600 text-warning" id="kpi-reclean">—</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Trend Chart --}}
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header">
            <h5 class="mb-0">Daily Booking Trend</h5>
        </div>
        <div class="card-body">
            <canvas id="bookingTrendChart" height="80"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let trendChart = null;

    const buildParams = () => {
        const range = $('#datatableRange').val().split(' - ');
        return {
            startDate:      range[0] ?? '',
            endDate:        range[1] ?? '',
            service_type:   $('#service_type').val(),
            cleaner_id:     $('#cleaner_id').val(),
            territory_id:   $('#territory_id').val() ?? 'all',
            booking_status: $('#booking_status').val(),
        };
    };

    const loadReport = () => {
        $.get('{{ route("report.fsm.bookings.chart") }}', buildParams(), function(data) {
            $('#kpi-total').text(data.total);
            $('#kpi-completion').text(data.completionRate + '%');
            $('#kpi-cancellation').text(data.cancellationRate + '%');
            $('#kpi-reclean').text(data.recleanRate + '%');

            const labels = data.trend.map(r => r.date);
            const totals = data.trend.map(r => r.total);
            const completed = data.trend.map(r => r.completed);

            if (trendChart) trendChart.destroy();
            const ctx = document.getElementById('bookingTrendChart').getContext('2d');
            trendChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [
                        { label: 'Total', data: totals, backgroundColor: 'rgba(54,162,235,0.5)' },
                        { label: 'Completed', data: completed, backgroundColor: 'rgba(75,192,75,0.7)' },
                    ]
                },
                options: { responsive: true, scales: { y: { beginAtZero: true } } }
            });
        });
    };

    const updateExportLink = () => {
        const p = buildParams();
        const qs = $.param(p);
        $('#export-btn').attr('href', '{{ route("report.fsm.bookings.export") }}?' + qs);
    };

    $('#refresh-report').on('click', function () { loadReport(); updateExportLink(); });
    $('#reset-report').on('click', function () {
        $('#datatableRange').val('');
        $('#service_type, #cleaner_id, #territory_id, #booking_status').val('all').selectpicker('refresh');
        loadReport();
    });

    // Load on page ready
    $(document).ready(function () {
        loadReport();
    });
</script>
@endpush
