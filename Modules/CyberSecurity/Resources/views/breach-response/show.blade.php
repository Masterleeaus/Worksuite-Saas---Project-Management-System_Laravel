@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center action-bar mb-4">
                <a href="{{ route('cybersecurity.breach-response.index') }}" class="btn btn-sm btn-outline-secondary mr-3">
                    <i class="fa fa-arrow-left"></i>
                </a>
                <h4 class="f-21 font-weight-700 text-capitalize mb-0">
                    @lang('cybersecurity::app.breach_response.breach_title') #{{ $breach->id }}
                </h4>
                <span class="ml-3 badge badge-{{ $breach->severity_badge }} f-14">
                    @lang('cybersecurity::app.breach_response.severities.' . $breach->severity)
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Breach Details -->
            <div class="card border-0 b-shadow-4 mb-4">
                <div class="card-body">
                    <h5 class="f-16 font-weight-700 mb-3">@lang('cybersecurity::app.breach_response.breach_details')</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-dark-grey font-weight-600 w-40">@lang('cybersecurity::app.breach_response.breach_title')</td>
                            <td>{{ $breach->title }}</td>
                        </tr>
                        <tr>
                            <td class="text-dark-grey font-weight-600">@lang('cybersecurity::app.breach_response.description')</td>
                            <td>{{ $breach->description }}</td>
                        </tr>
                        <tr>
                            <td class="text-dark-grey font-weight-600">@lang('cybersecurity::app.breach_response.status')</td>
                            <td>
                                <span class="badge badge-{{ $breach->status_badge }}">
                                    @lang('cybersecurity::app.breach_response.statuses.' . $breach->status)
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-dark-grey font-weight-600">@lang('cybersecurity::app.breach_response.detected_at')</td>
                            <td>{{ $breach->breach_detected_at->format(company_date_format() . ' H:i') }}</td>
                        </tr>
                        @if($breach->notification_deadline)
                        <tr>
                            <td class="text-dark-grey font-weight-600">@lang('cybersecurity::app.breach_response.notify_deadline')</td>
                            <td>
                                @php $overdue = $breach->notification_deadline->isPast() && !in_array($breach->status, ['notified','resolved']); @endphp
                                <span class="{{ $overdue ? 'text-danger font-weight-bold' : '' }}">
                                    {{ $breach->notification_deadline->format(company_date_format() . ' H:i') }}
                                    @if($overdue) — <strong>@lang('cybersecurity::app.breach_response.overdue')</strong> @endif
                                </span>
                            </td>
                        </tr>
                        @endif
                        @if($breach->notified_at)
                        <tr>
                            <td class="text-dark-grey font-weight-600">@lang('cybersecurity::app.breach_response.notified_at')</td>
                            <td class="text-success">{{ $breach->notified_at->format(company_date_format() . ' H:i') }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="text-dark-grey font-weight-600">@lang('cybersecurity::app.breach_response.affected_users_count')</td>
                            <td>{{ number_format($breach->affected_users_count) }}</td>
                        </tr>
                        @if($breach->affected_data_types)
                        <tr>
                            <td class="text-dark-grey font-weight-600">@lang('cybersecurity::app.breach_response.affected_data_types')</td>
                            <td>{{ $breach->affected_data_types }}</td>
                        </tr>
                        @endif
                        @if($breach->remediation_steps)
                        <tr>
                            <td class="text-dark-grey font-weight-600">@lang('cybersecurity::app.breach_response.remediation_steps')</td>
                            <td>{{ $breach->remediation_steps }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Update Status -->
            @if($breach->status !== 'resolved')
            <div class="card border-0 b-shadow-4">
                <div class="card-body">
                    <h5 class="f-16 font-weight-700 mb-3">@lang('cybersecurity::app.breach_response.update_status')</h5>
                    <form id="update-breach-form">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="f-14 text-dark-grey font-weight-600">@lang('cybersecurity::app.breach_response.new_status')</label>
                                    <select name="status" class="form-control">
                                        @if($breach->status === 'open')
                                            <option value="investigating">@lang('cybersecurity::app.breach_response.statuses.investigating')</option>
                                        @endif
                                        @if(in_array($breach->status, ['open','investigating']))
                                            <option value="notified">@lang('cybersecurity::app.breach_response.statuses.notified')</option>
                                        @endif
                                        <option value="resolved">@lang('cybersecurity::app.breach_response.statuses.resolved')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="f-14 text-dark-grey font-weight-600">@lang('cybersecurity::app.breach_response.assign_to')</label>
                                    <select name="assigned_to" class="form-control">
                                        <option value="">@lang('app.none')</option>
                                        @foreach(\App\Models\User::allAdmins() as $admin)
                                            <option value="{{ $admin->id }}" {{ $breach->assigned_to == $admin->id ? 'selected' : '' }}>
                                                {{ $admin->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="f-14 text-dark-grey font-weight-600">@lang('cybersecurity::app.breach_response.remediation_steps')</label>
                            <textarea name="remediation_steps" class="form-control" rows="4">{{ $breach->remediation_steps }}</textarea>
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
$('#update-breach-form').on('submit', function (e) {
    e.preventDefault();
    $.easyAjax({
        url: '{{ route("cybersecurity.breach-response.update", $breach->id) }}',
        type: 'PUT',
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
