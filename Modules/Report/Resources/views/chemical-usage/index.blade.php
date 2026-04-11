@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center action-bar">
        <h4 class="mb-0 f-18">Chemical Usage Report</h4>
        <a href="{{ route('report.fsm.chemical-usage.export') }}" id="export-btn" class="btn btn-secondary">
            <i class="material-icons f-16 mr-1">cloud_download</i> Export CSV
        </a>
    </div>

    {{-- Filters --}}
    <div class="row mt-3 mb-3">
        <div class="col-md-4">
            <input type="text" id="datatableRange" class="form-control"
                   placeholder="@lang('placeholders.dateRange')">
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
            The <strong>FSMStock</strong> module is not installed. Chemical usage tracking requires FSMStock.
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th class="text-right">Total Qty Used</th>
                                <th>Unit</th>
                                <th class="text-right">Bookings</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rows as $row)
                                <tr>
                                    <td>{{ $row->product_name }}</td>
                                    <td>{{ $row->category_name ?? 'Uncategorised' }}</td>
                                    <td class="text-right">{{ number_format($row->total_qty, 2) }}</td>
                                    <td>{{ $row->unit ?? '' }}</td>
                                    <td class="text-right">{{ $row->booking_count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        No chemical usage data for the selected period.
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
        const params = { startDate: range[0] ?? '', endDate: range[1] ?? '' };
        window.location.href = '{{ route("report.fsm.chemical-usage") }}?' + $.param(params);
    });
    $('#export-btn').on('click', function (e) {
        const range = $('#datatableRange').val().split(' - ');
        const params = { startDate: range[0] ?? '', endDate: range[1] ?? '' };
        $(this).attr('href', '{{ route("report.fsm.chemical-usage.export") }}?' + $.param(params));
    });
</script>
@endpush
