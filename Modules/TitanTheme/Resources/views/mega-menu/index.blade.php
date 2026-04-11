@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center action-bar">
        <h4 class="f-21 font-weight-normal text-capitalize mb-0">
            @lang('titantheme::titantheme.mega_menu')
        </h4>
        <a href="{{ route('titantheme.mega-menu.create') }}" class="btn btn-primary btn-sm">
            <i class="fa fa-plus mr-1"></i> New Mega Menu
        </a>
    </div>

    <div class="card mt-3">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>@lang('titantheme::titantheme.name')</th>
                        <th>@lang('titantheme::titantheme.icon')</th>
                        <th>Items</th>
                        <th>@lang('titantheme::titantheme.sort_order')</th>
                        <th>@lang('titantheme::titantheme.is_active')</th>
                        <th>@lang('app.action')</th>
                    </tr>
                </thead>
                <tbody id="sortable-menus">
                    @forelse($menus as $menu)
                    <tr data-id="{{ $menu->id }}">
                        <td class="f-14">
                            <strong>{{ $menu->title }}</strong>
                            @if($menu->required_module)
                                <br><small class="text-muted">Requires: {{ $menu->required_module }}</small>
                            @endif
                        </td>
                        <td class="f-14">
                            @if($menu->icon)
                                <i class="{{ $menu->icon }}"></i> {{ $menu->icon }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="f-14">{{ $menu->items->count() }}</td>
                        <td class="f-14">{{ $menu->sort_order }}</td>
                        <td>
                            @if($menu->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('titantheme.mega-menu.edit', $menu->id) }}"
                               class="btn btn-sm btn-outline-primary mr-1">
                                <i class="fa fa-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-danger delete-menu"
                                    data-menu-id="{{ $menu->id }}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted f-14">
                            No mega menus yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.delete-menu').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var id = this.dataset.menuId;
        Swal.fire({
            title: '@lang('messages.sweetAlertTitle')',
            text: '@lang('messages.recoverRecord')',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '@lang('messages.confirmDelete')',
        }).then(function (result) {
            if (result.isConfirmed) {
                axios.delete('/account/theme/mega-menu/' + id)
                    .then(function () { window.location.reload(); });
            }
        });
    });
});
</script>
@endpush
