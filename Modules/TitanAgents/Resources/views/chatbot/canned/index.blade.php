@extends('layouts/layoutMaster')

@section('title', 'Canned Responses — ' . $chatbot->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('titanagents.chatbot.show', $chatbot) }}" class="btn btn-outline-secondary me-3">
            <i class="ti ti-arrow-left"></i>
        </a>
        <h4 class="mb-0">Canned Responses — {{ $chatbot->name }}</h4>
    </div>

    @foreach(['success', 'error'] as $type)
        @if(session($type))
            <div class="alert alert-{{ $type === 'error' ? 'danger' : 'success' }} alert-dismissible fade show" role="alert">
                {{ session($type) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach

    <div class="card mb-4">
        <div class="card-header"><h6 class="mb-0">Add Canned Response</h6></div>
        <div class="card-body">
            <form method="POST" action="{{ route('titanagents.chatbot.canned.store', $chatbot) }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="title" class="form-control" placeholder="Title *" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="shortcut" class="form-control" placeholder="Shortcut (e.g. /hello)">
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="category" class="form-control" placeholder="Category">
                    </div>
                    <div class="col-12">
                        <textarea name="content" class="form-control" rows="3" placeholder="Response content *" required></textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Add Response</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Shortcut</th>
                            <th>Category</th>
                            <th>Uses</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($responses as $response)
                            <tr>
                                <td>{{ $response->title }}</td>
                                <td><code>{{ $response->shortcut ?? '—' }}</code></td>
                                <td>{{ $response->category ?? '—' }}</td>
                                <td>{{ $response->use_count }}</td>
                                <td>
                                    <span class="badge {{ $response->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($response->status) }}
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('titanagents.chatbot.canned.destroy', [$chatbot, $response]) }}" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">No canned responses yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($responses->hasPages())
            <div class="card-footer">{{ $responses->links() }}</div>
        @endif
    </div>
</div>
@endsection
