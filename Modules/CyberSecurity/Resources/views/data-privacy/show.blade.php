@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center action-bar mb-4">
                <a href="{{ route('cybersecurity.data-privacy.index') }}" class="btn btn-sm btn-outline-secondary mr-3">
                    <i class="fa fa-arrow-left"></i>
                </a>
                <h4 class="f-21 font-weight-700 text-capitalize mb-0">
                    @lang('cybersecurity::app.data_privacy.request') #{{ $privacyRequest->id }}
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 b-shadow-4 mb-4">
                <div class="card-body">
                    <h5 class="f-16 font-weight-700 mb-3">@lang('cybersecurity::app.data_privacy.request_details')</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-dark-grey font-weight-600 w-40">@lang('cybersecurity::app.data_privacy.type')</td>
                            <td>{{ $privacyRequest->type_label }}</td>
                        </tr>
                        <tr>
                            <td class="text-dark-grey font-weight-600">@lang('cybersecurity::app.data_privacy.requester')</td>
                            <td>{{ $privacyRequest->requester_name }} &lt;{{ $privacyRequest->requester_email }}&gt;</td>
                        </tr>
                        <tr>
                            <td class="text-dark-grey font-weight-600">@lang('cybersecurity::app.data_privacy.status')</td>
                            <td><span class="badge badge-{{ $privacyRequest->status_badge }}">@lang('cybersecurity::app.data_privacy.statuses.' . $privacyRequest->status)</span></td>
                        </tr>
                        <tr>
                            <td class="text-dark-grey font-weight-600">@lang('cybersecurity::app.data_privacy.due_date')</td>
                            <td>{{ $privacyRequest->due_date?->format(company_date_format()) ?? '—' }}</td>
                        </tr>
                        @if($privacyRequest->completed_at)
                        <tr>
                            <td class="text-dark-grey font-weight-600">@lang('cybersecurity::app.data_privacy.completed_at')</td>
                            <td>{{ $privacyRequest->completed_at->format(company_date_format()) }}</td>
                        </tr>
                        @endif
                        @if($privacyRequest->notes)
                        <tr>
                            <td class="text-dark-grey font-weight-600">@lang('cybersecurity::app.data_privacy.notes')</td>
                            <td>{{ $privacyRequest->notes }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Update Status -->
            @if($privacyRequest->status !== 'completed' && $privacyRequest->status !== 'rejected')
            <div class="card border-0 b-shadow-4">
                <div class="card-body">
                    <h5 class="f-16 font-weight-700 mb-3">@lang('cybersecurity::app.data_privacy.update_status')</h5>
                    <form id="update-status-form">
                        @csrf
                        <div class="form-group">
                            <label class="f-14 text-dark-grey font-weight-600">@lang('cybersecurity::app.data_privacy.new_status')</label>
                            <select name="status" class="form-control">
                                @if($privacyRequest->status === 'pending')
                                    <option value="in_progress">@lang('cybersecurity::app.data_privacy.statuses.in_progress')</option>
                                @endif
                                <option value="completed">@lang('cybersecurity::app.data_privacy.statuses.completed')</option>
                                <option value="rejected">@lang('cybersecurity::app.data_privacy.statuses.rejected')</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="f-14 text-dark-grey font-weight-600">@lang('cybersecurity::app.data_privacy.notes')</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">@lang('app.update')</button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$('#update-status-form').on('submit', function (e) {
    e.preventDefault();
    $.easyAjax({
        url: '{{ route("cybersecurity.data-privacy.update-status", $privacyRequest->id) }}',
        type: 'POST',
        data: $(this).serialize(),
        success: function (response) {
            if (response.status === 'success') {
                window.location.reload();
            }
        }
    });
});
</script>
@endpush
