@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12">
            <h4>AI Training Sources</h4>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Training Sources</div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead><tr><th>Type</th><th>Source URL</th><th>Content Preview</th><th>Added</th><th>Actions</th></tr></thead>
                        <tbody>
                            @forelse($embeddings as $emb)
                            <tr>
                                <td><span class="badge badge-secondary">{{ strtoupper($emb->source_type) }}</span></td>
                                <td>{{ $emb->source_url ? Str::limit($emb->source_url, 40) : '—' }}</td>
                                <td>{{ Str::limit($emb->content, 60) }}</td>
                                <td>{{ $emb->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <form method="POST" action="{{ route('titanreach.training.destroy', $emb->id) }}" class="d-inline" onsubmit="return confirm('Remove?')">
                                        @csrf
                                        <button class="btn btn-sm btn-danger">Remove</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted">No training sources yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-3">{{ $embeddings->links() }}</div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Add Training Source</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('titanreach.training.store') }}">
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger small">
                                @foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                            </div>
                        @endif

                        <div class="form-group">
                            <label>Type</label>
                            <select name="source_type" class="form-control" required>
                                @foreach(['url','text','qa','pdf'] as $t)
                                    <option value="{{ $t }}">{{ strtoupper($t) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Source URL (optional)</label>
                            <input type="url" name="source_url" class="form-control" placeholder="https://…">
                        </div>
                        <div class="form-group">
                            <label>Content <span class="text-danger">*</span></label>
                            <textarea name="content" class="form-control" rows="5" required></textarea>
                        </div>
                        <button class="btn btn-primary w-100">Add Source</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
