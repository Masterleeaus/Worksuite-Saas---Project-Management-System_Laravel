@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="heading-h1">@lang('projectroadmap::app.roadmap.title')</h3>
            @if($canManage)
                <div>
                    <a href="{{ route('roadmap.admin.create') }}" class="btn btn-primary mr-2">
                        <i class="fa fa-plus mr-1"></i> @lang('projectroadmap::app.roadmap.addItem')
                    </a>
                    <a href="{{ route('roadmap.admin.milestones') }}" class="btn btn-outline-secondary">
                        <i class="fa fa-flag mr-1"></i> @lang('projectroadmap::app.roadmap.milestones')
                    </a>
                </div>
            @endif
        </div>

        <div class="row roadmap-board">
            @foreach($statuses as $key => $label)
                <div class="col-md-2 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-{{ $statusColors[$key] ?? 'secondary' }} text-white text-center fw-bold py-2">
                            {{ $label }}
                        </div>
                        <div class="card-body p-2">
                            @if(isset($items[$key]) && $items[$key]->count())
                                @foreach($items[$key] as $item)
                                    <div class="card mb-2 roadmap-card">
                                        <div class="card-body p-2">
                                            <h6 class="mb-1 f-14 f-w-600">{{ $item->name }}</h6>
                                            @if($item->category)
                                                <span class="badge badge-light f-10 mb-1">{{ $item->category }}</span>
                                            @endif
                                            @if($item->description)
                                                <p class="f-12 text-muted mb-1">{{ Str::limit($item->description, 80) }}</p>
                                            @endif
                                            @if($item->target_release)
                                                <p class="f-11 text-muted mb-1">
                                                    <i class="fa fa-calendar-alt mr-1"></i> {{ $item->target_release }}
                                                </p>
                                            @endif
                                            <div class="d-flex justify-content-between align-items-center mt-1">
                                                @if($canVote)
                                                    <button class="btn btn-sm btn-{{ $item->hasVotedBy(user()->id) ? 'primary' : 'outline-primary' }} vote-btn"
                                                            data-id="{{ $item->id }}"
                                                            data-voted="{{ $item->hasVotedBy(user()->id) ? '1' : '0' }}"
                                                            title="@lang('projectroadmap::app.roadmap.vote')">
                                                        <i class="fa fa-thumbs-up mr-1"></i>
                                                        <span class="vote-count">{{ $item->votes }}</span>
                                                    </button>
                                                @else
                                                    <span class="text-muted f-12">
                                                        <i class="fa fa-thumbs-up mr-1"></i> {{ $item->votes }}
                                                    </span>
                                                @endif
                                                @if($canManage)
                                                    <div>
                                                        <a href="{{ route('roadmap.admin.edit', $item->id) }}"
                                                           class="btn btn-sm btn-light">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <button class="btn btn-sm btn-light delete-item" data-id="{{ $item->id }}">
                                                            <i class="fa fa-trash text-danger"></i>
                                                        </button>
                                                    </div>
                                                @endif
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
    // Feature voting
    $(document).on('click', '.vote-btn', function () {
        const btn = $(this);
        const itemId = btn.data('id');
        $.post('{{ url('account/roadmap') }}/' + itemId + '/vote', {_token: '{{ csrf_token() }}'})
            .done(function (res) {
                if (res.data) {
                    const d = res.data;
                    btn.find('.vote-count').text(d.votes);
                    if (d.voted) {
                        btn.removeClass('btn-outline-primary').addClass('btn-primary');
                    } else {
                        btn.removeClass('btn-primary').addClass('btn-outline-primary');
                    }
                }
            });
    });

    // Delete roadmap item
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
