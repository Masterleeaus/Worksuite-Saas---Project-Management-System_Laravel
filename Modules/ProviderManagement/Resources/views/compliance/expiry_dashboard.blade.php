@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <h4 class="mb-3">@lang('Compliance Expiry Dashboard')</h4>
        </div>
    </div>

    @if($expiring->isNotEmpty())
    <div class="card mb-4">
        <div class="card-header bg-warning text-white">
            <h5 class="mb-0">@lang('Expiring Soon') (within 30 days)</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead><tr><th>@lang('Employee')</th><th>@lang('Document')</th><th>@lang('Expiry Date')</th></tr></thead>
                <tbody>
                    @foreach($expiring as $item)
                        @foreach($item['expiries'] as $label => $date)
                        <tr>
                            <td>{{ $item['employee'] ?? "#".$item['employee_id'] }}</td>
                            <td>{{ $label }}</td>
                            <td>{{ $date }}</td>
                        </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if($expired->isNotEmpty())
    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">@lang('Expired Documents')</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead><tr><th>@lang('Employee')</th><th>@lang('Police Check Expiry')</th><th>@lang('Insurance Expiry')</th><th>@lang('WWCC Expiry')</th></tr></thead>
                <tbody>
                    @foreach($expired as $detail)
                    <tr>
                        <td>{{ optional($detail->user)->name }}</td>
                        <td>{{ $detail->police_check_expiry ?? '-' }}</td>
                        <td>{{ $detail->insurance_expiry ?? '-' }}</td>
                        <td>{{ $detail->wwcc_expiry ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if($expiring->isEmpty() && $expired->isEmpty())
        <div class="alert alert-success">@lang('All compliance documents are up to date.')</div>
    @endif
</div>
@endsection
