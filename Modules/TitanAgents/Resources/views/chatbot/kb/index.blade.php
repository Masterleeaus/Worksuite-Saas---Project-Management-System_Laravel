@extends('layouts/layoutMaster')

@section('title', 'Knowledge Base — ' . $chatbot->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('titanagents.chatbot.show', $chatbot) }}" class="btn btn-outline-secondary me-3">
            <i class="ti ti-arrow-left"></i>
        </a>
        <h4 class="mb-0">Knowledge Base — {{ $chatbot->name }}</h4>
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
        <div class="card-header"><h6 class="mb-0">Add Article</h6></div>
        <div class="card-body">
            <form method="POST" action="{{ route('titanagents.chatbot.kb.store', $chatbot) }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <input type="text" name="title" class="form-control" placeholder="Title *" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="category" class="form-control" placeholder="Category (optional)">
                    </div>
                    <div class="col-12">
                        <textarea name="content" class="form-control" rows="4" placeholder="Article content *" required></textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Add Article</button>
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
                            <th>Category</th>
                            <th>Status</th>
                            <th>Embedding</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($articles as $article)
                            <tr>
                                <td>{{ $article->title }}</td>
                                <td>{{ $article->category ?? '—' }}</td>
                                <td>
                                    <span class="badge {{ $article->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($article->status) }}
                                    </span>
                                </td>
                                <td>
                                    @php $colors = ['pending'=>'warning','processing'=>'info','done'=>'success','failed'=>'danger']; @endphp
                                    <span class="badge bg-{{ $colors[$article->embedding_status] ?? 'secondary' }}">
                                        {{ ucfirst($article->embedding_status) }}
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('titanagents.chatbot.kb.destroy', [$chatbot, $article]) }}" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No articles yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($articles->hasPages())
            <div class="card-footer">{{ $articles->links() }}</div>
        @endif
    </div>
</div>
@endsection
