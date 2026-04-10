<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 b-shadow-4 p-20">
            <div class="text-center">
                <h2 class="f-36 font-weight-700 {{ $scan['score'] >= 80 ? 'text-success' : ($scan['score'] >= 50 ? 'text-warning' : 'text-danger') }}">
                    {{ $scan['score'] }}%
                </h2>
                <p class="f-14 text-dark-grey mb-0">@lang('cybersecurity::app.security_scan.security_score')</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 b-shadow-4 p-20">
            <div class="text-center">
                <h2 class="f-36 font-weight-700 text-success">{{ $scan['pass'] }}</h2>
                <p class="f-14 text-dark-grey mb-0">@lang('cybersecurity::app.security_scan.passed')</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 b-shadow-4 p-20">
            <div class="text-center">
                <h2 class="f-36 font-weight-700 text-warning">{{ $scan['warn'] }}</h2>
                <p class="f-14 text-dark-grey mb-0">@lang('cybersecurity::app.security_scan.warnings')</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 b-shadow-4 p-20">
            <div class="text-center">
                <h2 class="f-36 font-weight-700 text-danger">{{ $scan['fail'] }}</h2>
                <p class="f-14 text-dark-grey mb-0">@lang('cybersecurity::app.security_scan.failed')</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 b-shadow-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="f-18 font-weight-600 mb-0">@lang('cybersecurity::app.security_scan.scan_results')</h5>
                    <span class="text-muted f-13">@lang('cybersecurity::app.security_scan.last_run'): {{ $scan['run_at'] }}</span>
                </div>
                @foreach($scan['results'] as $result)
                    <div class="d-flex align-items-start border-bottom py-3">
                        <div class="mr-3 mt-1">
                            @if($result['status'] === 'pass')
                                <span class="badge badge-success p-2"><i class="fa fa-check"></i></span>
                            @elseif($result['status'] === 'fail')
                                <span class="badge badge-danger p-2"><i class="fa fa-times"></i></span>
                            @else
                                <span class="badge badge-warning p-2"><i class="fa fa-exclamation"></i></span>
                            @endif
                        </div>
                        <div>
                            <p class="mb-1 f-14 font-weight-600">{{ $result['check'] }}</p>
                            <p class="mb-0 f-13 text-muted">{{ $result['details'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
