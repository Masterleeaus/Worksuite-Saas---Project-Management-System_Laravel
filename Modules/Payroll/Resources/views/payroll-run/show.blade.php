@extends('layouts.app')

@section('content')
<div class="w-100">
    <div class="d-flex justify-content-between align-items-center p-20 border-bottom-grey">
        <div>
            <h3 class="f-21 font-weight-normal mb-0">
                @lang('payroll::app.payrollRunDetails') #{{ $run->id }}
                @switch($run->status)
                    @case('approved') <span class="badge badge-success">Approved</span> @break
                    @case('exported') <span class="badge badge-primary">Exported</span> @break
                    @default <span class="badge badge-secondary">{{ ucfirst($run->status) }}</span>
                @endswitch
            </h3>
            <small class="text-muted">
                {{ $run->period_start->format(company()->date_format ?? 'd-m-Y') }}
                — {{ $run->period_end->format(company()->date_format ?? 'd-m-Y') }}
                @if($run->state) | {{ $run->state }} @endif
                | @lang('payroll::app.createdBy'): {{ optional($run->creator)->name }}
                @if($run->approver) | @lang('payroll::app.approvedBy'): {{ optional($run->approver)->name }} @endif
            </small>
        </div>
        <div>
            <a href="{{ route('payroll-runs.index') }}" class="btn btn-outline-secondary mr-2">
                <i class="fa fa-arrow-left"></i> @lang('app.back')
            </a>
            @if($run->isApproved())
            <a href="{{ route('payroll-runs.export-csv', $run->id) }}" class="btn btn-success mr-2">
                <i class="fa fa-file-csv"></i> @lang('payroll::app.exportCsv')
            </a>
            <a href="{{ route('payroll-runs.export-pdf', $run->id) }}" class="btn btn-danger">
                <i class="fa fa-file-pdf"></i> @lang('payroll::app.exportPdf')
            </a>
            @endif
        </div>
    </div>

    @foreach($byEmployee as $empId => $group)
    <div class="col-lg-12 px-20 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0">{{ optional($group['user'])->name }}</h5>
                <strong>@lang('payroll::app.totalPay'): ${{ number_format($group['total_pay'], 2) }}</strong>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>@lang('app.date')</th>
                            <th>@lang('payroll::app.hours')</th>
                            <th>@lang('payroll::app.rateType')</th>
                            <th>@lang('payroll::app.rateApplied')</th>
                            <th>@lang('payroll::app.grossPay')</th>
                            <th>@lang('payroll::app.commission')</th>
                            <th>@lang('payroll::app.totalPay')</th>
                            <th>@lang('payroll::app.flags')</th>
                            <th>@lang('payroll::app.auditNote')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($group['items'] as $item)
                        <tr @if($item->is_overridden) class="table-warning" @endif>
                            <td>{{ \Carbon\Carbon::parse($item->job_date)->format(company()->date_format ?? 'd-m-Y') }}</td>
                            <td>{{ number_format($item->hours_worked, 2) }}</td>
                            <td><span class="badge badge-secondary">{{ $item->rate_type }}</span></td>
                            <td>${{ number_format($item->rate_applied, 4) }}</td>
                            <td>${{ number_format($item->gross_pay, 2) }}</td>
                            <td>${{ number_format($item->commission_amount, 2) }}</td>
                            <td><strong>${{ number_format($item->total_pay, 2) }}</strong></td>
                            <td>
                                @if($item->is_public_holiday) <span class="badge badge-danger">PH</span> @endif
                                @if($item->is_overridden) <span class="badge badge-warning">OVR</span> @endif
                            </td>
                            <td>
                                @if($item->overrides->count())
                                    {{ $item->overrides->last()->reason }}
                                    <small class="text-muted">({{ optional($item->overrides->last()->overriddenBy)->name }})</small>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-weight-bold">
                            <td>@lang('app.total')</td>
                            <td>{{ number_format($group['items']->sum('hours_worked'), 2) }}</td>
                            <td colspan="2"></td>
                            <td>${{ number_format($group['items']->sum('gross_pay'), 2) }}</td>
                            <td>${{ number_format($group['items']->sum('commission_amount'), 2) }}</td>
                            <td>${{ number_format($group['total_pay'], 2) }}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
