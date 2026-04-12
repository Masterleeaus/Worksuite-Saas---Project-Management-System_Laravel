@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center action-bar">
        <h4 class="f-21 font-weight-normal text-capitalize mb-0">
            @lang('titantheme::titantheme.menu_builder')
        </h4>
    </div>

    <div class="card mt-3">
        <div class="card-body p-0">
            <table class="table table-hover mb-0" id="menu-table">
                <thead>
                    <tr>
                        <th>@lang('titantheme::titantheme.name')</th>
                        <th>@lang('titantheme::titantheme.route_name')</th>
                        <th>@lang('titantheme::titantheme.icon')</th>
                        <th>@lang('titantheme::titantheme.module')</th>
                        <th>@lang('app.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $menu)
                    <tr data-id="{{ $menu->id }}">
                        <td class="f-14">
                            <strong>{{ $menu->menu_name }}</strong>
                            @if($menu->translate_name)
                                <br><small class="text-muted">{{ $menu->translate_name }}</small>
                            @endif
                        </td>
                        <td class="f-14">
                            {{ $menu->route ?? '—' }}
                        </td>
                        <td class="f-14">
                            @if($menu->icon)
                                <i class="{{ $menu->icon }}"></i>
                                <small class="text-muted ml-1">{{ $menu->icon }}</small>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="f-14">
                            @if($menu->module)
                                <span class="badge badge-secondary">{{ $menu->module }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger delete-menu-item"
                                    data-menu-id="{{ $menu->id }}"
                                    data-menu-name="{{ $menu->menu_name }}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>

                    @if($menu->relationLoaded('children') && $menu->children->isNotEmpty())
                        @foreach($menu->children as $child)
                        <tr data-id="{{ $child->id }}" class="bg-light">
                            <td class="f-14 pl-4">
                                <span class="text-muted mr-1">↳</span>
                                {{ $child->menu_name }}
                                @if($child->translate_name)
                                    <br><small class="text-muted ml-3">{{ $child->translate_name }}</small>
                                @endif
                            </td>
                            <td class="f-14">{{ $child->route ?? '—' }}</td>
                            <td class="f-14">
                                @if($child->icon)
                                    <i class="{{ $child->icon }}"></i>
                                    <small class="text-muted ml-1">{{ $child->icon }}</small>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="f-14">
                                @if($child->module)
                                    <span class="badge badge-secondary">{{ $child->module }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-danger delete-menu-item"
                                        data-menu-id="{{ $child->id }}"
                                        data-menu-name="{{ $child->menu_name }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    @endif

                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted f-14">
                            @lang('titantheme::titantheme.no_menu_items')
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
document.querySelectorAll('.delete-menu-item').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var id   = this.dataset.menuId;
        var name = this.dataset.menuName;
        Swal.fire({
            title: '@lang('messages.sweetAlertTitle')',
            html: '@lang('titantheme::titantheme.confirm_delete_menu') <strong>' + name + '</strong>?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '@lang('messages.confirmDelete')',
            cancelButtonText: '@lang('app.cancel')',
        }).then(function (result) {
            if (result.isConfirmed) {
                axios.delete('/account/admin/menu/' + id)
                    .then(function () { window.location.reload(); })
                    .catch(function (err) {
                        var msg = err.response && err.response.data && err.response.data.message
                                  ? err.response.data.message
                                  : '@lang('messages.errorOccurred')';
                        Swal.fire({ icon: 'error', title: msg });
                    });
            }
        });
    });
});
</script>
@endpush
