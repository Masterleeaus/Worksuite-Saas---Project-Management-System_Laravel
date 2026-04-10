@extends('customerconnect::layouts.master')

@section('title', 'Tags')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Thread Tags</h4>
                </div>
                <div class="card-body">

                    @if(session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    {{-- Add Tag Form --}}
                    <form method="POST" action="{{ route('customerconnect.settings.tags.store') }}" class="mb-4">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="name" class="form-control" placeholder="Tag name" required maxlength="80">
                            <input type="color" name="color" class="form-control form-control-color" value="#6b7280" title="Tag colour">
                            <button type="submit" class="btn btn-primary">Add Tag</button>
                        </div>
                    </form>

                    {{-- Tags List --}}
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Colour</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tags as $tag)
                            <tr>
                                <td>
                                    <span class="badge" style="background-color: {{ $tag->color ?? '#6b7280' }}">
                                        {{ $tag->name }}
                                    </span>
                                </td>
                                <td>{{ $tag->color ?? '—' }}</td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('customerconnect.settings.tags.destroy', $tag->id) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Delete tag?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-muted">No tags yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $tags->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
