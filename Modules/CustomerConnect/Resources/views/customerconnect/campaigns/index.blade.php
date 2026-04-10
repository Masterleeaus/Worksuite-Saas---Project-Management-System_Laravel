@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Campaigns</h4>
        <a class="btn btn-primary" href="{{ route('customerconnect.campaigns.create') }}">New Campaign</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($campaigns as $c)
                        <tr>
                            <td><a href="{{ route('customerconnect.campaigns.show', $c) }}">{{ $c->name }}</a></td>
                            <td><span class="badge badge-secondary">{{ $c->status }}</span></td>
                            <td class="text-right">
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('customerconnect.campaigns.edit', $c) }}">Edit</a>
                                <form class="d-inline" method="POST" action="{{ route('customerconnect.campaigns.destroy', $c) }}">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete campaign?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-muted">No campaigns yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($campaigns,'links'))
            <div class="card-footer">{{ $campaigns->links() }}</div>
        @endif
    </div>
</div>
@endsection
