@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center action-bar">
        <h4 class="f-21 font-weight-normal text-capitalize mb-0">
            @lang('titantheme::titantheme.theme_presets')
        </h4>
        <a href="{{ route('titantheme.customizer.index') }}" class="btn btn-primary btn-sm">
            <i class="fa fa-paint-brush mr-1"></i> @lang('titantheme::titantheme.live_customizer')
        </a>
    </div>

    <div class="card mt-3">
        <div class="card-body p-0">
            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                <span class="f-14 font-weight-bold">All Presets</span>
                <div>
                    <a href="{{ route('titantheme.presets.create') }}" class="btn btn-outline-primary btn-sm mr-2">
                        <i class="fa fa-plus mr-1"></i> @lang('titantheme::titantheme.create_preset')
                    </a>
                    @if($activePreset)
                    <form method="POST" action="{{ route('titantheme.presets.deactivate') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                            @lang('titantheme::titantheme.deactivate')
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>@lang('titantheme::titantheme.name')</th>
                        <th>Colours</th>
                        <th>Fonts</th>
                        <th>Status</th>
                        <th>@lang('app.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($presets as $preset)
                    <tr>
                        <td class="f-14">
                            <strong>{{ $preset->name }}</strong>
                            @if($preset->description)
                                <br><small class="text-muted">{{ $preset->description }}</small>
                            @endif
                        </td>
                        <td>
                            @foreach(['primary_color','secondary_color','accent_color'] as $c)
                                @if($preset->$c)
                                <span style="display:inline-block;width:16px;height:16px;border-radius:3px;background:{{ $preset->$c }};border:1px solid #dee2e6;margin-right:2px;"
                                      title="{{ $c }}"></span>
                                @endif
                            @endforeach
                        </td>
                        <td class="f-14 text-muted">
                            {{ $preset->heading_font ?? '—' }}
                        </td>
                        <td>
                            @if($preset->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            @if(!$preset->is_active)
                            <form method="POST" action="{{ route('titantheme.presets.activate', $preset->id) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-success mr-1"
                                        title="@lang('titantheme::titantheme.activate')">
                                    <i class="fa fa-check"></i>
                                </button>
                            </form>
                            @endif
                            <a href="{{ route('titantheme.presets.edit', $preset->id) }}"
                               class="btn btn-sm btn-outline-primary mr-1"
                               title="@lang('app.edit')">
                                <i class="fa fa-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-danger delete-preset"
                                    data-preset-id="{{ $preset->id }}"
                                    title="@lang('app.delete')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted f-14">
                            No theme presets yet. <a href="{{ route('titantheme.customizer.index') }}">Open the Live Customizer</a> to create one.
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
document.querySelectorAll('.delete-preset').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var id = this.dataset.presetId;
        Swal.fire({
            title: '@lang('messages.sweetAlertTitle')',
            text: '@lang('messages.recoverRecord')',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '@lang('messages.confirmDelete')',
            cancelButtonText: '@lang('app.cancel')',
        }).then(function (result) {
            if (result.isConfirmed) {
                axios.delete('/account/theme/presets/' + id)
                    .then(function () {
                        window.location.reload();
                    });
            }
        });
    });
});
</script>
@endpush
