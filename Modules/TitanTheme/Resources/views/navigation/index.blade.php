@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center action-bar">
        <h4 class="f-21 font-weight-normal text-capitalize mb-0">
            @lang('titantheme::titantheme.navigation')
        </h4>
    </div>

    <div class="row mt-3">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="f-14 font-weight-bold">Sidebar Navigation</span>
                    <button class="btn btn-primary btn-sm" id="btn-add-nav">
                        <i class="fa fa-plus mr-1"></i> @lang('titantheme::titantheme.add_item')
                    </button>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>@lang('titantheme::titantheme.label')</th>
                                <th>Panel</th>
                                <th>Type</th>
                                <th>Active</th>
                                <th>@lang('app.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sidebarItems as $item)
                            <tr>
                                <td class="f-14">
                                    @if($item->parent_id)<span class="text-muted ml-3">↳ </span>@endif
                                    @if($item->icon)<i class="{{ $item->icon }} mr-1"></i>@endif
                                    {{ $item->label }}
                                </td>
                                <td><span class="badge badge-secondary">{{ $item->panel }}</span></td>
                                <td><span class="badge badge-light">{{ $item->item_type }}</span></td>
                                <td>
                                    @if($item->is_active)
                                        <i class="fa fa-check text-success"></i>
                                    @else
                                        <i class="fa fa-times text-danger"></i>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-xs btn-outline-danger delete-nav-item"
                                            data-item-id="{{ $item->id }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3 f-14">No custom navigation items.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add nav item modal --}}
<div class="modal fade" id="addNavModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title f-16">@lang('titantheme::titantheme.add_item')</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="add-nav-form" method="POST" action="{{ route('titantheme.navigation.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="f-14 text-dark-grey mb-1">@lang('titantheme::titantheme.label') <span class="text-danger">*</span></label>
                        <input type="text" name="label" class="form-control f-14" required>
                    </div>
                    <div class="form-group">
                        <label class="f-14 text-dark-grey mb-1">@lang('titantheme::titantheme.panel')</label>
                        <select name="panel" class="form-control f-14">
                            <option value="sidebar">Sidebar</option>
                            <option value="header">Header</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="f-14 text-dark-grey mb-1">@lang('titantheme::titantheme.url')</label>
                        <input type="text" name="url" class="form-control f-14">
                    </div>
                    <div class="form-group">
                        <label class="f-14 text-dark-grey mb-1">@lang('titantheme::titantheme.icon')</label>
                        <input type="text" name="icon" class="form-control f-14" placeholder="fa fa-link">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="nav_is_active" name="is_active" value="1" checked>
                        <label class="form-check-label f-14" for="nav_is_active">@lang('titantheme::titantheme.is_active')</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">@lang('app.cancel')</button>
                    <button type="submit" class="btn btn-primary btn-sm">@lang('app.save')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('btn-add-nav').addEventListener('click', function () {
    $('#addNavModal').modal('show');
});
document.querySelectorAll('.delete-nav-item').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var id = this.dataset.itemId;
        Swal.fire({
            title: '@lang('messages.sweetAlertTitle')',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '@lang('messages.confirmDelete')',
        }).then(function (r) {
            if (r.isConfirmed) {
                axios.delete('/account/theme/navigation/' + id)
                    .then(function () { window.location.reload(); });
            }
        });
    });
});
</script>
@endpush
