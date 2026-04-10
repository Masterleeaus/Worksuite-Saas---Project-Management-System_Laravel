@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between action-bar">
                <div>
                    <h4 class="f-21 font-weight-700 text-capitalize mb-0">@lang('cybersecurity::app.compliance.title')</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Framework Selector -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="d-flex mb-3">
                @foreach($frameworks as $key => $label)
                    <a href="{{ route('cybersecurity.compliance.index', ['framework' => $key]) }}"
                       class="btn btn-sm mr-2 {{ $framework === $key ? 'btn-primary' : 'btn-outline-secondary' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Score Summary -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 b-shadow-4 p-20">
                <div class="text-center">
                    <h2 class="f-36 font-weight-700 text-primary">{{ $compliancePercent }}%</h2>
                    <p class="f-14 text-dark-grey mb-0">@lang('cybersecurity::app.compliance.score')</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 b-shadow-4 p-20">
                <div class="text-center">
                    <h2 class="f-36 font-weight-700 text-success">{{ $compliantCount }}</h2>
                    <p class="f-14 text-dark-grey mb-0">@lang('cybersecurity::app.compliance.compliant')</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 b-shadow-4 p-20">
                <div class="text-center">
                    <h2 class="f-36 font-weight-700 text-danger">{{ $nonCompliantCount }}</h2>
                    <p class="f-14 text-dark-grey mb-0">@lang('cybersecurity::app.compliance.non_compliant')</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 b-shadow-4 p-20">
                <div class="text-center">
                    <h2 class="f-36 font-weight-700 text-secondary">{{ $totalCount }}</h2>
                    <p class="f-14 text-dark-grey mb-0">@lang('cybersecurity::app.compliance.total_items')</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Checklist -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 b-shadow-4">
                <div class="card-body">
                    <h5 class="f-18 font-weight-600 mb-4">{{ $frameworks[$framework] }} @lang('cybersecurity::app.compliance.checklist')</h5>
                    @foreach($checklistItems as $item)
                        <div class="d-flex align-items-start justify-content-between border-bottom py-3 compliance-item" data-key="{{ $item['key'] }}">
                            <div class="d-flex align-items-start flex-grow-1 mr-3">
                                <div class="mr-3 mt-1">
                                    @if($item['status'] === 'compliant')
                                        <span class="badge badge-success p-2"><i class="fa fa-check"></i></span>
                                    @elseif($item['status'] === 'non_compliant')
                                        <span class="badge badge-danger p-2"><i class="fa fa-times"></i></span>
                                    @elseif($item['status'] === 'not_applicable')
                                        <span class="badge badge-secondary p-2"><i class="fa fa-minus"></i></span>
                                    @else
                                        <span class="badge badge-warning p-2"><i class="fa fa-clock"></i></span>
                                    @endif
                                </div>
                                <div>
                                    <p class="mb-1 f-14 font-weight-600">{{ $item['label'] }}</p>
                                    @if($item['notes'])
                                        <p class="mb-0 f-12 text-muted">{{ $item['notes'] }}</p>
                                    @endif
                                    @if($item['reviewed_at'])
                                        <p class="mb-0 f-11 text-muted">@lang('cybersecurity::app.compliance.reviewed_at'): {{ $item['reviewed_at'] }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <select class="form-control form-control-sm compliance-status-select mr-2"
                                        data-key="{{ $item['key'] }}"
                                        data-framework="{{ $framework }}"
                                        style="min-width: 160px;">
                                    <option value="pending" {{ $item['status'] === 'pending' ? 'selected' : '' }}>@lang('cybersecurity::app.compliance.status.pending')</option>
                                    <option value="compliant" {{ $item['status'] === 'compliant' ? 'selected' : '' }}>@lang('cybersecurity::app.compliance.status.compliant')</option>
                                    <option value="non_compliant" {{ $item['status'] === 'non_compliant' ? 'selected' : '' }}>@lang('cybersecurity::app.compliance.status.non_compliant')</option>
                                    <option value="not_applicable" {{ $item['status'] === 'not_applicable' ? 'selected' : '' }}>@lang('cybersecurity::app.compliance.status.not_applicable')</option>
                                </select>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('.compliance-status-select').on('change', function () {
        var key = $(this).data('key');
        var framework = $(this).data('framework');
        var status = $(this).val();

        $.easyAjax({
            url: '{{ route("cybersecurity.compliance.update") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                framework: framework,
                item_key: key,
                status: status
            },
            success: function (response) {
                if (response.status === 'success') {
                    window.location.reload();
                }
            }
        });
    });
});
</script>
@endpush
