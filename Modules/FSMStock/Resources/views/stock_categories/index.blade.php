@extends('fsmstock::layouts.master')
@section('fsmstock_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Stock Categories</h4>
    <a href="{{ route('fsmstock.stock-categories.create') }}" class="btn btn-primary btn-sm">+ New Category</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>#</th><th>Name</th><th>Description</th><th>Active</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ \Str::limit($category->description, 60) }}</td>
                    <td>
                        <span class="badge bg-{{ $category->active ? 'success' : 'secondary' }}">
                            {{ $category->active ? 'Yes' : 'No' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('fsmstock.stock-categories.edit', $category->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form method="POST" action="{{ route('fsmstock.stock-categories.destroy', $category->id) }}" class="d-inline"
                              onsubmit="return confirm('Delete this category?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted">No categories found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $categories->links() }}</div>
</div>
@endsection
