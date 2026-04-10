@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between action-bar">
                <div>
                    <h4 class="f-21 font-weight-700 text-capitalize mb-0">@lang('cybersecurity::app.security_scan.title')</h4>
                    <p class="text-muted f-14 mb-0">@lang('cybersecurity::app.security_scan.description')</p>
                </div>
                <div>
                    <button id="run-scan-btn" class="btn btn-primary">
                        <i class="fa fa-search mr-1"></i> @lang('cybersecurity::app.security_scan.run_scan')
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="scan-results-container" class="mt-4">
        @if($lastScanResults)
            @include('cybersecurity::security-scan.ajax.results', ['scan' => $lastScanResults])
        @else
            <div class="card border-0 b-shadow-4">
                <div class="card-body text-center py-5">
                    <i class="fa fa-shield-alt f-60 text-muted mb-3" style="font-size:60px;"></i>
                    <h5 class="f-18 text-muted">@lang('cybersecurity::app.security_scan.no_scan_yet')</h5>
                    <p class="text-muted">@lang('cybersecurity::app.security_scan.click_to_start')</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
$('#run-scan-btn').on('click', function () {
    var $btn = $(this);
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> @lang("cybersecurity::app.security_scan.scanning")');

    $.easyAjax({
        url: '{{ route("cybersecurity.security-scan.run") }}',
        type: 'POST',
        data: { _token: '{{ csrf_token() }}' },
        success: function (response) {
            if (response.status === 'success') {
                $('#scan-results-container').html(response.html);
            }
            $btn.prop('disabled', false).html('<i class="fa fa-search mr-1"></i> @lang("cybersecurity::app.security_scan.run_scan")');
        },
        error: function () {
            $btn.prop('disabled', false).html('<i class="fa fa-search mr-1"></i> @lang("cybersecurity::app.security_scan.run_scan")');
        }
    });
});
</script>
@endpush
