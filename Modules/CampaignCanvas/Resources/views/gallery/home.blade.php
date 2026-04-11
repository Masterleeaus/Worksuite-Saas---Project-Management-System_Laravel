@extends('campaigncanvas::layouts.master')

@section('campaigncanvas-content')
<div class="content-wrapper">
    <div class="row content-header">
        <div class="col-sm-8">
            <h1 class="content-header-title">{{ __('campaigncanvas::campaigncanvas.gallery_title') }}</h1>
        </div>
        <div class="col-sm-4">
            <a href="{{ route('campaigncanvas.editor.create') }}" class="btn btn-primary float-right">
                <i class="fa fa-plus"></i> {{ __('campaigncanvas::campaigncanvas.new_design') }}
            </a>
        </div>
    </div>

    <div class="row">
        @forelse($documents as $doc)
        <div class="col-md-3 mb-4">
            <div class="card">
                <a href="{{ route('campaigncanvas.editor.edit', $doc->uuid) }}">
                    @if($doc->preview)
                        <img src="{{ asset('storage/' . $doc->preview) }}" class="card-img-top" alt="{{ $doc->name }}" style="height:150px;object-fit:cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height:150px;">
                            <i class="fa fa-image fa-3x text-muted"></i>
                        </div>
                    @endif
                </a>
                <div class="card-body p-2">
                    <h6 class="card-title mb-1 text-truncate">{{ $doc->name }}</h6>
                    <small class="text-muted">{{ $doc->updated_at->diffForHumans() }}</small>
                    <div class="mt-2 d-flex justify-content-between">
                        <a href="{{ route('campaigncanvas.editor.edit', $doc->uuid) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <button class="btn btn-sm btn-outline-secondary"
                                onclick="ccDuplicate('{{ $doc->uuid }}')">
                            {{ __('campaigncanvas::campaigncanvas.duplicate') }}
                        </button>
                        <button class="btn btn-sm btn-outline-danger"
                                onclick="ccDelete('{{ $doc->uuid }}')">
                            {{ __('campaigncanvas::campaigncanvas.delete') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fa fa-paint-brush fa-3x text-muted mb-3"></i>
                <p class="text-muted">No designs yet. <a href="{{ route('campaigncanvas.editor.create') }}">Create your first design.</a></p>
            </div>
        </div>
        @endforelse
    </div>

    <div class="row">
        <div class="col-12">{{ $documents->links() }}</div>
    </div>
</div>

<script>
const CC_CSRF   = '{{ csrf_token() }}';
const CC_ROUTES = {
    duplicate: (uuid) => `/account/campaign-canvas/documents/${uuid}/duplicate`,
    destroy:   (uuid) => `/account/campaign-canvas/documents/${uuid}`,
    gallery:   '{{ route('campaigncanvas.gallery.index') }}',
};

function ccDuplicate(uuid) {
    fetch(CC_ROUTES.duplicate(uuid), {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': CC_CSRF, 'Accept': 'application/json'},
    })
    .then(res => {
        if (!res.ok) throw new Error('Duplicate failed');
        return res.json();
    })
    .then(() => { window.location = CC_ROUTES.gallery; })
    .catch(() => { alert('Could not duplicate design. Please try again.'); });
}

function ccDelete(uuid) {
    if (!confirm('Delete this design?')) return;
    fetch(CC_ROUTES.destroy(uuid), {
        method: 'DELETE',
        headers: {'X-CSRF-TOKEN': CC_CSRF, 'Accept': 'application/json'},
    })
    .then(() => { window.location.reload(); });
}
</script>
@endsection
