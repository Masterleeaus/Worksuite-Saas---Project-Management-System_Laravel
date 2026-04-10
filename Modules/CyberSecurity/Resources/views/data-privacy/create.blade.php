@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center action-bar mb-4">
                <a href="{{ route('cybersecurity.data-privacy.index') }}" class="btn btn-sm btn-outline-secondary mr-3">
                    <i class="fa fa-arrow-left"></i>
                </a>
                <h4 class="f-21 font-weight-700 text-capitalize mb-0">@lang('cybersecurity::app.data_privacy.new_request')</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 b-shadow-4">
                <div class="card-body">
                    <form id="data-privacy-form">
                        @csrf
                        <div class="form-group">
                            <label class="f-14 text-dark-grey font-weight-600">@lang('cybersecurity::app.data_privacy.request_type') <span class="text-danger">*</span></label>
                            <select name="type" class="form-control">
                                <option value="access">@lang('cybersecurity::app.data_privacy.types.access')</option>
                                <option value="deletion">@lang('cybersecurity::app.data_privacy.types.deletion')</option>
                                <option value="rectification">@lang('cybersecurity::app.data_privacy.types.rectification')</option>
                                <option value="portability">@lang('cybersecurity::app.data_privacy.types.portability')</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="f-14 text-dark-grey font-weight-600">@lang('cybersecurity::app.data_privacy.requester_name') <span class="text-danger">*</span></label>
                            <input type="text" name="requester_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="f-14 text-dark-grey font-weight-600">@lang('cybersecurity::app.data_privacy.requester_email') <span class="text-danger">*</span></label>
                            <input type="email" name="requester_email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="f-14 text-dark-grey font-weight-600">@lang('cybersecurity::app.data_privacy.notes')</label>
                            <textarea name="notes" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">@lang('app.save')</button>
                            <a href="{{ route('cybersecurity.data-privacy.index') }}" class="btn btn-outline-secondary ml-2">@lang('app.cancel')</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 b-shadow-4 bg-light-blue">
                <div class="card-body">
                    <h6 class="f-14 font-weight-700"><i class="fa fa-info-circle mr-1"></i> @lang('cybersecurity::app.data_privacy.info_title')</h6>
                    <p class="f-13 text-muted">@lang('cybersecurity::app.data_privacy.info_text')</p>
                    <ul class="f-13 text-muted pl-3">
                        <li>@lang('cybersecurity::app.data_privacy.info_access')</li>
                        <li>@lang('cybersecurity::app.data_privacy.info_deletion')</li>
                        <li>@lang('cybersecurity::app.data_privacy.info_deadline')</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$('#data-privacy-form').on('submit', function (e) {
    e.preventDefault();
    $.easyAjax({
        url: '{{ route("cybersecurity.data-privacy.store") }}',
        type: 'POST',
        data: $(this).serialize(),
        success: function (response) {
            if (response.status === 'success') {
                window.location = '{{ route("cybersecurity.data-privacy.index") }}';
            }
        }
    });
});
</script>
@endpush
