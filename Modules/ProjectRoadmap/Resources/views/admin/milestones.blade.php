@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="heading-h1">@lang('projectroadmap::app.roadmap.milestones')</h3>
            <div>
                <button class="btn btn-primary" data-toggle="modal" data-target="#addMilestoneModal">
                    <i class="fa fa-plus mr-1"></i> @lang('projectroadmap::app.roadmap.addMilestone')
                </button>
                <a href="{{ route('roadmap.admin.index') }}" class="btn btn-secondary ml-2">
                    <i class="fa fa-arrow-left mr-1"></i> @lang('app.back')
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>@lang('app.title')</th>
                            <th>@lang('app.status')</th>
                            <th>Target Date</th>
                            <th>Completed</th>
                            <th>@lang('app.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($milestones as $ms)
                            <tr>
                                <td>
                                    <strong>{{ $ms->title }}</strong>
                                    @if($ms->description)
                                        <br><small class="text-muted">{{ Str::limit($ms->description, 100) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $statusColors[$ms->status] ?? 'secondary' }}">
                                        {{ $statuses[$ms->status] ?? $ms->status }}
                                    </span>
                                </td>
                                <td>{{ $ms->target_date ? $ms->target_date->format('d M Y') : '—' }}</td>
                                <td>{{ $ms->completed_date ? $ms->completed_date->format('d M Y') : '—' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-light edit-milestone"
                                            data-id="{{ $ms->id }}"
                                            data-title="{{ $ms->title }}"
                                            data-description="{{ $ms->description }}"
                                            data-status="{{ $ms->status }}"
                                            data-target_date="{{ $ms->target_date?->format('Y-m-d') }}"
                                            data-completed_date="{{ $ms->completed_date?->format('Y-m-d') }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light delete-milestone" data-id="{{ $ms->id }}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">No milestones yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Add Milestone Modal --}}
    <div class="modal fade" id="addMilestoneModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('projectroadmap::app.roadmap.addMilestone')</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="add-milestone-form" method="POST" action="{{ route('roadmap.admin.milestones.store') }}">
                    @csrf
                    <div class="modal-body">
                        @include('projectroadmap::admin.partials.milestone-fields')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('app.cancel')</button>
                        <button type="submit" class="btn btn-primary">@lang('app.save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Milestone Modal --}}
    <div class="modal fade" id="editMilestoneModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Milestone</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="edit-milestone-form" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        @include('projectroadmap::admin.partials.milestone-fields', ['edit' => true])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('app.cancel')</button>
                        <button type="submit" class="btn btn-primary">@lang('app.update')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Open edit modal
    $(document).on('click', '.edit-milestone', function () {
        const d = $(this).data();
        const form = $('#edit-milestone-form');
        form.attr('action', '{{ url('account/roadmap/admin/milestones') }}/' + d.id);
        form.find('[name=title]').val(d.title);
        form.find('[name=description]').val(d.description);
        form.find('[name=status]').val(d.status).selectpicker('refresh');
        form.find('[name=target_date]').val(d.target_date);
        form.find('[name=completed_date]').val(d.completed_date);
        $('#editMilestoneModal').modal('show');
    });

    // Delete
    $(document).on('click', '.delete-milestone', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: '@lang('messages.areYouSure')',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '@lang('app.delete')',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url('account/roadmap/admin/milestones') }}/' + id,
                    type: 'DELETE',
                    data: {_token: '{{ csrf_token() }}'},
                    success: function () { window.location.reload(); }
                });
            }
        });
    });

    // Reload after save
    $('#add-milestone-form, #edit-milestone-form').on('submit', function (e) {
        e.preventDefault();
        const form = $(this);
        $.ajax({
            url: form.attr('action'),
            type: form.find('[name=_method]').val() || 'POST',
            data: form.serialize(),
            success: function () { window.location.reload(); },
        });
    });
</script>
@endpush
