@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center action-bar">
        <h4 class="f-21 font-weight-normal text-capitalize mb-0">
            Edit Mega Menu: {{ $menu->title }}
        </h4>
        <a href="{{ route('titantheme.mega-menu.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa fa-arrow-left mr-1"></i> @lang('app.back')
        </a>
    </div>

    <div class="row mt-3">
        {{-- Menu settings --}}
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header f-14 font-weight-bold">Menu Settings</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('titantheme.mega-menu.update', $menu->id) }}">
                        @csrf
                        @method('PUT')
                        @include('titantheme::mega-menu._form', ['menu' => $menu])
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary btn-sm">@lang('app.save')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Menu items --}}
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="f-14 font-weight-bold">Menu Items</span>
                    <button class="btn btn-primary btn-sm" id="btn-add-item">
                        <i class="fa fa-plus mr-1"></i> @lang('titantheme::titantheme.add_item')
                    </button>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0" id="items-table">
                        <thead>
                            <tr>
                                <th>@lang('titantheme::titantheme.label')</th>
                                <th>Type</th>
                                <th>Active</th>
                                <th>@lang('app.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($menu->allItems as $item)
                            <tr data-item-id="{{ $item->id }}">
                                <td class="f-14">
                                    @if($item->parent_id)
                                        <span class="text-muted ml-3">↳ </span>
                                    @endif
                                    @if($item->icon)<i class="{{ $item->icon }} mr-1"></i>@endif
                                    {{ $item->label }}
                                </td>
                                <td>
                                    <span class="badge badge-secondary">{{ $item->item_type }}</span>
                                    @if($item->is_featured)
                                        <span class="badge badge-warning">Featured</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->is_active)
                                        <i class="fa fa-check text-success"></i>
                                    @else
                                        <i class="fa fa-times text-danger"></i>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-xs btn-outline-danger delete-item"
                                            data-item-id="{{ $item->id }}"
                                            data-menu-id="{{ $menu->id }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr id="no-items-row">
                                <td colspan="4" class="text-center text-muted py-3 f-14">No items yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add item modal --}}
<div class="modal fade" id="addItemModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title f-16">@lang('titantheme::titantheme.add_item')</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="add-item-form" method="POST"
                  action="{{ route('titantheme.mega-menu.items.store', $menu->id) }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="f-14 text-dark-grey mb-1">@lang('titantheme::titantheme.label') <span class="text-danger">*</span></label>
                        <input type="text" name="label" class="form-control f-14" required>
                    </div>
                    <div class="form-group">
                        <label class="f-14 text-dark-grey mb-1">Type</label>
                        <select name="item_type" class="form-control f-14">
                            <option value="link">Link</option>
                            <option value="group">Group</option>
                            <option value="featured">Featured</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="f-14 text-dark-grey mb-1">@lang('titantheme::titantheme.url')</label>
                        <input type="text" name="url" class="form-control f-14" placeholder="https://...">
                    </div>
                    <div class="form-group">
                        <label class="f-14 text-dark-grey mb-1">@lang('titantheme::titantheme.icon')</label>
                        <input type="text" name="icon" class="form-control f-14" placeholder="fa fa-home">
                    </div>
                    <div class="form-group">
                        <label class="f-14 text-dark-grey mb-1">@lang('titantheme::titantheme.description')</label>
                        <input type="text" name="description" class="form-control f-14">
                    </div>
                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input" id="is_active_item" name="is_active" value="1" checked>
                        <label class="form-check-label f-14" for="is_active_item">@lang('titantheme::titantheme.is_active')</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_featured_item" name="is_featured" value="1">
                        <label class="form-check-label f-14" for="is_featured_item">Featured</label>
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
document.getElementById('btn-add-item').addEventListener('click', function () {
    $('#addItemModal').modal('show');
});

document.querySelectorAll('.delete-item').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var itemId = this.dataset.itemId;
        var menuId = this.dataset.menuId;
        Swal.fire({
            title: '@lang('messages.sweetAlertTitle')',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '@lang('messages.confirmDelete')',
        }).then(function (result) {
            if (result.isConfirmed) {
                axios.delete('/account/theme/mega-menu/' + menuId + '/items/' + itemId)
                    .then(function () { window.location.reload(); });
            }
        });
    });
});
</script>
@endpush
