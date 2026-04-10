<div class="d-flex align-items-center py-1" style="padding-left: {{ $depth * 24 }}px;">
    <span class="badge bg-secondary me-2">{{ ucfirst($territory->type) }}</span>
    <span class="fw-semibold me-2">{{ $territory->name }}</span>
    @if($territory->zip_codes)
        <span class="small text-muted me-2">{{ $territory->zip_codes }}</span>
    @endif
    <a href="{{ route('fsmcore.territories.edit', $territory->id) }}" class="btn btn-sm btn-outline-primary me-1">Edit</a>
    <form method="POST" action="{{ route('fsmcore.territories.destroy', $territory->id) }}" class="d-inline" onsubmit="return confirm('Delete territory?')">
        @csrf <button class="btn btn-sm btn-outline-danger">Delete</button>
    </form>
</div>
@foreach($territory->children as $child)
    @include('fsmcore::territories._tree_item', ['territory' => $child, 'depth' => $depth + 1])
@endforeach
