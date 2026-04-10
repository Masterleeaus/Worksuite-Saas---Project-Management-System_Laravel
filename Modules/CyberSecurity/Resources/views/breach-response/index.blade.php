@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between action-bar">
                <div>
                    <h4 class="f-21 font-weight-700 text-capitalize mb-0">@lang('cybersecurity::app.breach_response.title')</h4>
                    <p class="text-muted f-14 mb-0">@lang('cybersecurity::app.breach_response.description')</p>
                </div>
                <div>
                    <a href="{{ route('cybersecurity.breach-response.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus mr-1"></i> @lang('cybersecurity::app.breach_response.report_breach')
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mt-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 b-shadow-4 p-20 text-center">
                <h2 class="f-36 font-weight-700 text-danger">{{ $openCount }}</h2>
                <p class="f-14 text-dark-grey mb-0">@lang('cybersecurity::app.breach_response.open')</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 b-shadow-4 p-20 text-center">
                <h2 class="f-36 font-weight-700 text-warning">{{ $investigatingCount }}</h2>
                <p class="f-14 text-dark-grey mb-0">@lang('cybersecurity::app.breach_response.investigating')</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 b-shadow-4 p-20 text-center">
                <h2 class="f-36 font-weight-700 text-success">{{ $resolvedCount }}</h2>
                <p class="f-14 text-dark-grey mb-0">@lang('cybersecurity::app.breach_response.resolved')</p>
            </div>
        </div>
    </div>

    <!-- 72h notification warning banner -->
    @php
        $overdueNotification = $breaches->filter(function ($b) {
            return in_array($b->status, ['open', 'investigating'])
                && $b->notification_deadline
                && $b->notification_deadline->isPast();
        });
    @endphp
    @if($overdueNotification->count())
        <div class="alert alert-danger d-flex align-items-center mb-4">
            <i class="fa fa-exclamation-triangle mr-2"></i>
            <strong>@lang('cybersecurity::app.breach_response.overdue_warning', ['count' => $overdueNotification->count()])</strong>
        </div>
    @endif

    <!-- Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 b-shadow-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('cybersecurity::app.breach_response.breach_title')</th>
                                <th>@lang('cybersecurity::app.breach_response.severity')</th>
                                <th>@lang('cybersecurity::app.breach_response.status')</th>
                                <th>@lang('cybersecurity::app.breach_response.detected_at')</th>
                                <th>@lang('cybersecurity::app.breach_response.notify_deadline')</th>
                                <th>@lang('app.action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($breaches as $breach)
                                <tr>
                                    <td>{{ $breach->id }}</td>
                                    <td>{{ $breach->title }}</td>
                                    <td>
                                        <span class="badge badge-{{ $breach->severity_badge }}">
                                            @lang('cybersecurity::app.breach_response.severities.' . $breach->severity)
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $breach->status_badge }}">
                                            @lang('cybersecurity::app.breach_response.statuses.' . $breach->status)
                                        </span>
                                    </td>
                                    <td>{{ $breach->breach_detected_at->format(company_date_format()) }}</td>
                                    <td>
                                        @if($breach->notification_deadline)
                                            @php $overdue = $breach->notification_deadline->isPast() && !in_array($breach->status, ['notified','resolved']); @endphp
                                            <span class="{{ $overdue ? 'text-danger font-weight-bold' : '' }}">
                                                {{ $breach->notification_deadline->format(company_date_format() . ' H:i') }}
                                                @if($overdue) <i class="fa fa-exclamation-triangle text-danger"></i> @endif
                                            </span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('cybersecurity.breach-response.show', $breach->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger delete-breach" data-id="{{ $breach->id }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">@lang('cybersecurity::app.breach_response.no_breaches')</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $breaches->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$('.delete-breach').on('click', function () {
    var id = $(this).data('id');
    Swal.fire({
        title: "@lang('messages.sweetAlertTitle')",
        text: "@lang('messages.confirmation_password')",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: "@lang('messages.confirmDelete')"
    }).then((result) => {
        if (result.isConfirmed) {
            $.easyAjax({
                url: '{{ url("account/cybersecurity/breach-response") }}/' + id,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function (response) {
                    if (response.status === 'success') {
                        window.location.reload();
                    }
                }
            });
        }
    });
});
</script>
@endpush
