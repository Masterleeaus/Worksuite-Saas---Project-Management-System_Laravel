@extends('layouts.app')

@section('content')
<div class="w-100">
    <div class="d-flex justify-content-between align-items-center p-20 border-bottom-grey">
        <h3 class="f-21 font-weight-normal mb-0">@lang('payroll::app.menu.payrollRuns')</h3>
        <x-forms.button-primary icon="plus" id="createPayrollRun">
            @lang('payroll::app.newPayrollRun')
        </x-forms.button-primary>
    </div>

    <div class="col-lg-12 p-20">
        <div class="table-responsive">
            <table class="table table-hover border-0 w-100 dataTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('payroll::app.periodStart')</th>
                        <th>@lang('payroll::app.periodEnd')</th>
                        <th>@lang('payroll::app.state')</th>
                        <th>@lang('app.status')</th>
                        <th>@lang('payroll::app.createdBy')</th>
                        <th>@lang('payroll::app.approvedBy')</th>
                        <th>@lang('app.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($runs as $run)
                    <tr>
                        <td>{{ $run->id }}</td>
                        <td>{{ $run->period_start->format(company()->date_format ?? 'd-m-Y') }}</td>
                        <td>{{ $run->period_end->format(company()->date_format ?? 'd-m-Y') }}</td>
                        <td>{{ $run->state ?? 'All' }}</td>
                        <td>
                            @switch($run->status)
                                @case('draft') <span class="badge badge-secondary">Draft</span> @break
                                @case('preview') <span class="badge badge-warning">Preview</span> @break
                                @case('approved') <span class="badge badge-success">Approved</span> @break
                                @case('exported') <span class="badge badge-primary">Exported</span> @break
                            @endswitch
                        </td>
                        <td>{{ optional($run->creator)->name }}</td>
                        <td>{{ optional($run->approver)->name ?? '—' }}</td>
                        <td class="d-flex gap-1">
                            @if(!$run->isApproved())
                            <a href="{{ route('payroll-runs.preview', $run->id) }}" class="btn btn-sm btn-warning">
                                <i class="fa fa-eye"></i> @lang('payroll::app.preview')
                            </a>
                            @endif
                            <a href="{{ route('payroll-runs.show', $run->id) }}" class="btn btn-sm btn-info">
                                <i class="fa fa-list"></i> @lang('app.view')
                            </a>
                            @if($run->isApproved())
                            <a href="{{ route('payroll-runs.export-csv', $run->id) }}" class="btn btn-sm btn-success">
                                <i class="fa fa-file-csv"></i> CSV
                            </a>
                            <a href="{{ route('payroll-runs.export-pdf', $run->id) }}" class="btn btn-sm btn-danger">
                                <i class="fa fa-file-pdf"></i> PDF
                            </a>
                            @endif
                            @if(!$run->isApproved())
                            <form method="POST" action="{{ route('payroll-runs.destroy', $run->id) }}" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('@lang('messages.confirmDelete')')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $runs->links() }}
        </div>
    </div>
</div>

<div class="modal fade" id="payrollRunModal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div id="payrollRunModalContent"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#createPayrollRun').click(function () {
        $.ajax({
            url: '{{ route("payroll-runs.create") }}',
            success: function (data) {
                $('#payrollRunModalContent').html(data.html);
                $('#payrollRunModal').modal('show');
            }
        });
    });
</script>
@endpush
