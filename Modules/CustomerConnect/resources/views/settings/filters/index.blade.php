@extends('customerconnect::layouts.master')

@section('title', 'Saved Filters')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Saved Inbox Filters</h4>
                </div>
                <div class="card-body">

                    @if(session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Default</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($filters as $filter)
                            <tr>
                                <td>{{ $filter->name }}</td>
                                <td>
                                    @if($filter->is_default)
                                        <span class="badge bg-success">Default</span>
                                    @else
                                        <form method="POST" action="{{ route('customerconnect.settings.filters.default', $filter->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">Set Default</button>
                                        </form>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('customerconnect.settings.filters.destroy', $filter->id) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Delete filter?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-muted">No saved filters yet. Save a filter from the inbox.</td></tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
