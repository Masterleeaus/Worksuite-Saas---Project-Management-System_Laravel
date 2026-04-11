@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="heading-h1">@lang('projectroadmap::app.roadmap.title')</h3>
            <div>
                <a href="{{ route('roadmap.admin.create') }}" class="btn btn-primary mr-2">
                    <i class="fa fa-plus mr-1"></i> @lang('projectroadmap::app.roadmap.addItem')
                </a>
                <a href="{{ route('roadmap.admin.milestones') }}" class="btn btn-outline-secondary mr-2">
                    <i class="fa fa-flag mr-1"></i> @lang('projectroadmap::app.roadmap.milestones')
                </a>
                <a href="{{ route('roadmap.index') }}" class="btn btn-outline-info">
                    <i class="fa fa-eye mr-1"></i> @lang('app.view')
                </a>
            </div>
        </div>

        <div class="row roadmap-board">
            @foreach($statuses as $key => $label)
                <div class="col-md-2 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-{{ $statusColors[$key] ?? 'secondary' }} text-white text-center fw-bold py-2">
                            {{ $label }}
                        </div>
                        <div class="card-body p-2">
                            @if(isset($items) && $items->where('status', $key)->count())
                                @foreach($items->where('status', $key) as $item)
                                    <div class="card mb-2">
                                        <div class="card-body p-2">
                                            <h6 class="mb-1 f-14 f-w-600">{{ $item->name }}</h6>
                                            @if($item->category)
                                                <span class="badge badge-light f-10 mb-1">{{ $item->category }}</span>
                                            @endif
                                            @if(!$item->is_public)
                                                <span class="badge badge-warning f-10 mb-1">Private</span>
                                            @endif
                                            @if($item->target_release)
                                                <p class="f-11 text-muted mb-1">
                                                    <i class="fa fa-calendar-alt mr-1"></i> {{ $item->target_release }}
                                                </p>
                                            @endif
                                            <div class="d-flex justify-content-between align-items-center mt-1">
                                                <span class="text-muted f-12">
                                                    <i class="fa fa-thumbs-up mr-1"></i> {{ $item->votes }}
                                                </span>
                                                <div>
                                                    <a href="{{ route('roadmap.admin.edit', $item->id) }}"
                                                       class="btn btn-sm btn-light">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-light delete-item" data-id="{{ $item->id }}">
                                                        <i class="fa fa-trash text-danger"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-center text-muted f-12 mt-3">@lang('projectroadmap::app.roadmap.noItems')</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).on('click', '.delete-item', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: '@lang('messages.areYouSure')',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '@lang('app.delete')',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url('account/roadmap/admin') }}/' + id,
                    type: 'DELETE',
                    data: {_token: '{{ csrf_token() }}'},
                    success: function () {
                        window.location.reload();
                    }
                });
            }
        });
    });
</script>
@endpush
