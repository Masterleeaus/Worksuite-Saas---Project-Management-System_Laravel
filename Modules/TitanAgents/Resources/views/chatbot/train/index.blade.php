@extends('layouts/layoutMaster')

@section('title', 'Training — ' . $chatbot->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('titanagents.chatbot.show', $chatbot) }}" class="btn btn-outline-secondary me-3">
            <i class="ti ti-arrow-left"></i>
        </a>
        <h4 class="mb-0">Training — {{ $chatbot->name }}</h4>
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
        <div class="card-body d-flex gap-3">
            <form method="POST" action="{{ route('titanagents.chatbot.train.all', $chatbot) }}">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-brain me-1"></i> Train Pending Articles
                </button>
            </form>
            <form method="POST" action="{{ route('titanagents.chatbot.train.retrain', $chatbot) }}" onsubmit="return confirm('This will retrain all articles. Continue?')">
                @csrf
                <button type="submit" class="btn btn-warning">
                    <i class="ti ti-refresh me-1"></i> Retrain All
                </button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Article Embedding Status</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Article</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Embedding Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($articles as $article)
                            @php $colors = ['pending'=>'warning','processing'=>'info','done'=>'success','failed'=>'danger']; @endphp
                            <tr>
                                <td>{{ $article->title }}</td>
                                <td>{{ $article->category ?? '—' }}</td>
                                <td>
                                    <span class="badge {{ $article->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($article->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $colors[$article->embedding_status] ?? 'secondary' }}">
                                        {{ ucfirst($article->embedding_status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No articles found. <a href="{{ route('titanagents.chatbot.kb.index', $chatbot) }}">Add some articles</a> first.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
