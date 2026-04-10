@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center action-bar mb-4">
                <a href="{{ route('cybersecurity.breach-response.index') }}" class="btn btn-sm btn-outline-secondary mr-3">
                    <i class="fa fa-arrow-left"></i>
                </a>
                <h4 class="f-21 font-weight-700 text-capitalize mb-0">@lang('cybersecurity::app.breach_response.report_breach')</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 b-shadow-4">
                <div class="card-body">
                    <form id="breach-form">
                        @csrf
                        <div class="form-group">
                            <label class="f-14 text-dark-grey font-weight-600">@lang('cybersecurity::app.breach_response.breach_title') <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" required placeholder="@lang('cybersecurity::app.breach_response.title_placeholder')">
                        </div>
                        <div class="form-group">
                            <label class="f-14 text-dark-grey font-weight-600">@lang('cybersecurity::app.breach_response.description') <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="5" required placeholder="@lang('cybersecurity::app.breach_response.description_placeholder')"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="f-14 text-dark-grey font-weight-600">@lang('cybersecurity::app.breach_response.severity') <span class="text-danger">*</span></label>
                                    <select name="severity" class="form-control">
                                        <option value="low">@lang('cybersecurity::app.breach_response.severities.low')</option>
                                        <option value="medium" selected>@lang('cybersecurity::app.breach_response.severities.medium')</option>
                                        <option value="high">@lang('cybersecurity::app.breach_response.severities.high')</option>
                                        <option value="critical">@lang('cybersecurity::app.breach_response.severities.critical')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="f-14 text-dark-grey font-weight-600">@lang('cybersecurity::app.breach_response.detected_at') <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="breach_detected_at" class="form-control" required value="{{ now()->format('Y-m-d\TH:i') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="f-14 text-dark-grey font-weight-600">@lang('cybersecurity::app.breach_response.affected_users_count')</label>
                                    <input type="number" name="affected_users_count" class="form-control" min="0" value="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="f-14 text-dark-grey font-weight-600">@lang('cybersecurity::app.breach_response.affected_data_types')</label>
                                    <input type="text" name="affected_data_types" class="form-control" placeholder="@lang('cybersecurity::app.breach_response.affected_data_placeholder')">
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-danger">
                                <i class="fa fa-exclamation-triangle mr-1"></i> @lang('cybersecurity::app.breach_response.submit_report')
                            </button>
                            <a href="{{ route('cybersecurity.breach-response.index') }}" class="btn btn-outline-secondary ml-2">@lang('app.cancel')</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 b-shadow-4" style="background: #fff5f5;">
                <div class="card-body">
                    <h6 class="f-14 font-weight-700 text-danger"><i class="fa fa-exclamation-triangle mr-1"></i> @lang('cybersecurity::app.breach_response.legal_note_title')</h6>
                    <p class="f-13 text-muted">@lang('cybersecurity::app.breach_response.legal_note_text')</p>
                    <ul class="f-13 text-muted pl-3">
                        <li>@lang('cybersecurity::app.breach_response.legal_gdpr')</li>
                        <li>@lang('cybersecurity::app.breach_response.legal_ndb')</li>
                        <li>@lang('cybersecurity::app.breach_response.legal_deadline')</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$('#breach-form').on('submit', function (e) {
    e.preventDefault();
    $.easyAjax({
        url: '{{ route("cybersecurity.breach-response.store") }}',
        type: 'POST',
        data: $(this).serialize(),
        success: function (response) {
            if (response.status === 'success') {
                window.location = '{{ route("cybersecurity.breach-response.index") }}';
            }
        }
    });
});
</script>
@endpush
