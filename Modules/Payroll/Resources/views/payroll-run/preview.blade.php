@extends('layouts.app')

@section('content')
<div class="w-100">
    <div class="d-flex justify-content-between align-items-center p-20 border-bottom-grey">
        <div>
            <h3 class="f-21 font-weight-normal mb-0">
                @lang('payroll::app.payrollRunPreview')
                <span class="badge badge-warning">{{ strtoupper($run->status) }}</span>
            </h3>
            <small class="text-muted">
                {{ $run->period_start->format(company()->date_format ?? 'd-m-Y') }}
                — {{ $run->period_end->format(company()->date_format ?? 'd-m-Y') }}
                @if($run->state) | {{ $run->state }} @endif
            </small>
        </div>
        <div>
            <a href="{{ route('payroll-runs.index') }}" class="btn btn-outline-secondary mr-2">
                <i class="fa fa-arrow-left"></i> @lang('app.back')
            </a>
            @if($run->status === 'preview' && in_array('admin', user_roles()))
            <form method="POST" action="{{ route('payroll-runs.approve', $run->id) }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success"
                    onclick="return confirm('@lang('payroll::app.confirmApprove')')">
                    <i class="fa fa-check"></i> @lang('payroll::app.approveRun')
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Job card submission form for calculating/recalculating --}}
    @if($run->status === 'draft' || $run->status === 'preview')
    <div class="col-lg-12 p-20">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">@lang('payroll::app.submitJobCards')</h5>
                <small class="text-muted">@lang('payroll::app.submitJobCardsHelp')</small>
            </div>
            <div class="card-body">
                <form id="jobCardsForm" method="POST" action="{{ route('payroll-runs.preview', $run->id) }}">
                    @csrf
                    <div id="jobCardsContainer">
                        <div class="row job-card-row mb-2 border-bottom pb-2">
                            <div class="col-md-2">
                                <select name="job_cards[0][user_id]" class="form-control form-control-sm" required>
                                    <option value="">@lang('app.employee')</option>
                                    @php $employees = \App\Models\User::join('employee_details','employee_details.user_id','users.id')->select('users.id','users.name')->get(); @endphp
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="datetime-local" name="job_cards[0][job_start]" class="form-control form-control-sm" placeholder="Job Start" required>
                            </div>
                            <div class="col-md-2">
                                <input type="datetime-local" name="job_cards[0][job_end]" class="form-control form-control-sm" placeholder="Job End" required>
                            </div>
                            <div class="col-md-1">
                                <input type="number" name="job_cards[0][rooms_cleaned]" class="form-control form-control-sm" placeholder="Rooms" min="0" value="0">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="job_cards[0][contract_ref]" class="form-control form-control-sm" placeholder="Contract Ref">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="job_cards[0][source_ref]" class="form-control form-control-sm" placeholder="Job ID / Ref">
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-row">✕</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="addJobCard" class="btn btn-sm btn-outline-primary mt-2">
                        + @lang('payroll::app.addJobCard')
                    </button>
                    <button type="submit" class="btn btn-primary mt-2">
                        <i class="fa fa-calculator"></i> @lang('payroll::app.calculatePayroll')
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Preview table per employee --}}
    @if($run->lineItems->count())
    @foreach($byEmployee as $empId => $group)
    <div class="col-lg-12 px-20 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0">{{ optional($group['user'])->name }}</h5>
                <strong>Total: ${{ number_format($group['total_pay'], 2) }}</strong>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>@lang('app.date')</th>
                            <th>@lang('payroll::app.jobStart')</th>
                            <th>@lang('payroll::app.jobEnd')</th>
                            <th>@lang('payroll::app.hoursWorked')</th>
                            <th>@lang('payroll::app.rateType')</th>
                            <th>@lang('payroll::app.rateApplied')</th>
                            <th>@lang('payroll::app.grossPay')</th>
                            <th>@lang('payroll::app.rooms')</th>
                            <th>@lang('payroll::app.commission')</th>
                            <th>@lang('payroll::app.totalPay')</th>
                            <th>@lang('payroll::app.flags')</th>
                            @if($run->status === 'preview') <th>@lang('app.action')</th> @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($group['items'] as $item)
                        <tr @if($item->is_overridden) class="table-warning" @endif>
                            <td>{{ \Carbon\Carbon::parse($item->job_date)->format(company()->date_format ?? 'd-m-Y') }}</td>
                            <td>{{ $item->job_start ? \Carbon\Carbon::parse($item->job_start)->format('H:i') : '—' }}</td>
                            <td>{{ $item->job_end ? \Carbon\Carbon::parse($item->job_end)->format('H:i') : '—' }}</td>
                            <td>{{ number_format($item->hours_worked, 2) }}</td>
                            <td><span class="badge badge-{{ ['base'=>'secondary','night'=>'dark','saturday'=>'info','sunday'=>'primary','public_holiday'=>'danger'][$item->rate_type] ?? 'secondary' }}">{{ $item->rate_type }}</span></td>
                            <td>${{ number_format($item->rate_applied, 4) }}</td>
                            <td>${{ number_format($item->gross_pay, 2) }}</td>
                            <td>{{ $item->rooms_cleaned }}</td>
                            <td>${{ number_format($item->commission_amount, 2) }}</td>
                            <td><strong>${{ number_format($item->total_pay, 2) }}</strong></td>
                            <td>
                                @if($item->is_public_holiday) <span class="badge badge-danger">PH</span> @endif
                                @if($item->is_overridden) <span class="badge badge-warning">Overridden</span> @endif
                            </td>
                            @if($run->status === 'preview')
                            <td>
                                <button class="btn btn-xs btn-outline-warning override-btn"
                                    data-item="{{ $item->id }}"
                                    data-run="{{ $run->id }}"
                                    data-current="{{ $item->total_pay }}">
                                    @lang('payroll::app.override')
                                </button>
                                @if($item->overrides->count())
                                    <span class="text-muted ml-1" title="{{ $item->overrides->last()->reason }}">
                                        <i class="fa fa-info-circle"></i>
                                    </span>
                                @endif
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-weight-bold">
                            <td colspan="6">@lang('app.total')</td>
                            <td>${{ number_format($group['items']->sum('gross_pay'), 2) }}</td>
                            <td>{{ $group['items']->sum('rooms_cleaned') }}</td>
                            <td>${{ number_format($group['items']->sum('commission_amount'), 2) }}</td>
                            <td><strong>${{ number_format($group['total_pay'], 2) }}</strong></td>
                            <td colspan="{{ $run->status === 'preview' ? 2 : 1 }}"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @endforeach
    @else
    <div class="col-lg-12 p-20">
        <div class="alert alert-info">@lang('payroll::app.noLineItems')</div>
    </div>
    @endif
</div>

{{-- Override modal --}}
<div class="modal fade" id="overrideModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('payroll::app.overrideLineItem')</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <form id="overrideForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('payroll::app.currentPay')</label>
                        <input type="text" id="currentPayDisplay" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>@lang('payroll::app.newTotalPay') *</label>
                        <input type="number" step="0.01" min="0" name="new_total_pay" id="new_total_pay" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>@lang('payroll::app.overrideReason') *</label>
                        <textarea name="reason" class="form-control" rows="3" required placeholder="@lang('payroll::app.overrideReasonPlaceholder')"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <x-forms.button-cancel data-dismiss="modal">@lang('app.cancel')</x-forms.button-cancel>
                    <x-forms.button-primary id="saveOverrideBtn" icon="check">@lang('app.save')</x-forms.button-primary>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let overrideRunId, overrideItemId;

    $('body').on('click', '.override-btn', function () {
        overrideRunId = $(this).data('run');
        overrideItemId = $(this).data('item');
        $('#currentPayDisplay').val('$' + parseFloat($(this).data('current')).toFixed(2));
        $('#new_total_pay').val($(this).data('current'));
        $('#overrideModal').modal('show');
    });

    $('#saveOverrideBtn').click(function () {
        var url = '{{ route("payroll-runs.override-line-item", [":run", ":item"]) }}'
            .replace(':run', overrideRunId)
            .replace(':item', overrideItemId);

        $.easyAjax({
            url: url,
            container: '#overrideForm',
            type: 'POST',
            data: $('#overrideForm').serialize(),
            success: function (data) {
                if (data.status === 'success') {
                    window.location.reload();
                }
            }
        });
    });

    // Add job card row
    let jobCardIndex = 1;
    $('#addJobCard').click(function () {
        var template = $('.job-card-row:first').html()
            .replace(/\[0\]/g, '[' + jobCardIndex + ']');
        var $row = $('<div class="row job-card-row mb-2 border-bottom pb-2">' + template + '</div>');
        $('#jobCardsContainer').append($row);
        jobCardIndex++;
    });

    $(document).on('click', '.remove-row', function () {
        if ($('.job-card-row').length > 1) {
            $(this).closest('.job-card-row').remove();
        }
    });
</script>
@endpush
