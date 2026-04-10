@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between action-bar">
                <div>
                    <h4 class="f-21 font-weight-700 text-capitalize mb-0">@lang('cybersecurity::app.data_privacy.title')</h4>
                    <p class="text-muted f-14 mb-0">@lang('cybersecurity::app.data_privacy.description')</p>
                </div>
                <div>
                    <a href="{{ route('cybersecurity.data-privacy.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus mr-1"></i> @lang('cybersecurity::app.data_privacy.new_request')
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mt-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 b-shadow-4 p-20 text-center">
                <h2 class="f-36 font-weight-700 text-warning">{{ $pendingCount }}</h2>
                <p class="f-14 text-dark-grey mb-0">@lang('cybersecurity::app.data_privacy.pending')</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 b-shadow-4 p-20 text-center">
                <h2 class="f-36 font-weight-700 text-info">{{ $inProgressCount }}</h2>
                <p class="f-14 text-dark-grey mb-0">@lang('cybersecurity::app.data_privacy.in_progress')</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 b-shadow-4 p-20 text-center">
                <h2 class="f-36 font-weight-700 text-success">{{ $completedCount }}</h2>
                <p class="f-14 text-dark-grey mb-0">@lang('cybersecurity::app.data_privacy.completed')</p>
            </div>
        </div>
    </div>

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
                                <th>@lang('cybersecurity::app.data_privacy.type')</th>
                                <th>@lang('cybersecurity::app.data_privacy.requester')</th>
                                <th>@lang('cybersecurity::app.data_privacy.status')</th>
                                <th>@lang('cybersecurity::app.data_privacy.due_date')</th>
                                <th>@lang('app.action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($requests as $req)
                                <tr>
                                    <td>{{ $req->id }}</td>
                                    <td>
                                        @switch($req->type)
                                            @case('access') <span class="badge badge-info">@lang('cybersecurity::app.data_privacy.types.access')</span> @break
                                            @case('deletion') <span class="badge badge-danger">@lang('cybersecurity::app.data_privacy.types.deletion')</span> @break
                                            @case('rectification') <span class="badge badge-warning">@lang('cybersecurity::app.data_privacy.types.rectification')</span> @break
                                            @case('portability') <span class="badge badge-secondary">@lang('cybersecurity::app.data_privacy.types.portability')</span> @break
                                        @endswitch
                                    </td>
                                    <td>
                                        {{ $req->requester_name }}<br>
                                        <small class="text-muted">{{ $req->requester_email }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $req->status_badge }}">
                                            @lang('cybersecurity::app.data_privacy.statuses.' . $req->status)
                                        </span>
                                    </td>
                                    <td>
                                        @if($req->due_date)
                                            <span class="{{ $req->due_date->isPast() && $req->status !== 'completed' ? 'text-danger font-weight-bold' : '' }}">
                                                {{ $req->due_date->format(company_date_format()) }}
                                            </span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('cybersecurity.data-privacy.show', $req->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger delete-privacy-request" data-id="{{ $req->id }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">@lang('cybersecurity::app.data_privacy.no_requests')</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $requests->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$('.delete-privacy-request').on('click', function () {
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
                url: '{{ url("account/cybersecurity/data-privacy") }}/' + id,
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
