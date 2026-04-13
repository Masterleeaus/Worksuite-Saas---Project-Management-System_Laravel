@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4>Contact Lists</h4>
            <a href="{{ route('titanreach.lists.create') }}" class="btn btn-success">+ New List</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Contacts</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lists as $list)
                    <tr>
                        <td>
                            <a href="{{ route('titanreach.lists.show', $list->id) }}">{{ $list->name }}</a>
                        </td>
                        <td class="text-muted">{{ Str::limit($list->description ?? '', 60) }}</td>
                        <td>
                            <span class="badge badge-secondary">{{ $list->contacts_count }}</span>
                        </td>
                        <td class="small text-muted">{{ $list->created_at->diffForHumans() }}</td>
                        <td>
                            <a href="{{ route('titanreach.lists.show', $list->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                            <form method="POST" action="{{ route('titanreach.lists.destroy', $list->id) }}"
                                  class="d-inline" onsubmit="return confirm('Delete this list?')">
                                @csrf
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">No contact lists yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $lists->links() }}
    </div>
</div>
@endsection
