@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Audiences</h4>
        <a class="btn btn-primary" href="{{ route('customerconnect.audiences.create') }}">New Audience</a>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Name</th><th>Source</th><th class="text-right">Actions</th></tr></thead>
                    <tbody>
                    @forelse($audiences as $a)
                        <tr>
                            <td>{{ $a->name }}</td>
                            <td><span class="badge badge-secondary">{{ $a->source }}</span></td>
                            <td class="text-right">
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('customerconnect.audiences.edit', $a) }}">Edit</a>
                                <form class="d-inline" method="POST" action="{{ route('customerconnect.audiences.destroy', $a) }}">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete audience?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-muted">No audiences yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($audiences,'links'))
            <div class="card-footer">{{ $audiences->links() }}</div>
        @endif
    </div>
</div>
@endsection
