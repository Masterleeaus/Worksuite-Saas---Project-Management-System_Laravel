@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="ti-plus me-2"></i>Extras Items</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('clientpulse.admin.extras.requests') }}" class="btn btn-outline-primary">
                View Client Requests
            </a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
                <i class="ti-plus me-1"></i>Add Item
            </button>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Sort</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Active</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td><span class="text-muted">{{ $item->sort_order }}</span></td>
                            <td><strong>{{ $item->name }}</strong></td>
                            <td><span class="text-muted">{{ $item->description ?? '—' }}</span></td>
                            <td>
                                @if($item->active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                {{-- Quick toggle active --}}
                                <form method="POST" action="{{ route('clientpulse.admin.extras.update', $item->id) }}"
                                      class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="active" value="{{ $item->active ? '0' : '1' }}">
                                    <button type="submit" class="btn btn-sm btn-outline-secondary me-1">
                                        {{ $item->active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('clientpulse.admin.extras.destroy', $item->id) }}"
                                      class="d-inline"
                                      onsubmit="return confirm('Delete this extras item?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No extras items yet. Add one above.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Add Item Modal --}}
<div class="modal fade" id="addItemModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('clientpulse.admin.extras.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Extras Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required maxlength="150">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" name="description" class="form-control" maxlength="500">
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="0" min="0">
                        </div>
                        <div class="col-6 d-flex align-items-end">
                            <div class="form-check pb-2">
                                <input type="checkbox" name="active" value="1" id="addActive"
                                       class="form-check-input" checked>
                                <label class="form-check-label" for="addActive">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Item</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
