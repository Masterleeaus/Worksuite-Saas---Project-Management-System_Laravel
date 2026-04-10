@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Unsubscribes</h4>
        <form class="d-flex" method="get">
            <input name="q" class="form-control" placeholder="Search..." value="{{ request('q') }}">
        </form>
    </div>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <form method="post" action="{{ route('customerconnect.settings.unsubscribes.store') }}">
                @csrf
                <div class="row g-2">
                    <div class="col-md-3"><input class="form-control" name="email" placeholder="Email"></div>
                    <div class="col-md-2"><input class="form-control" name="phone" placeholder="Phone"></div>
                    <div class="col-md-2">
                        <select class="form-control" name="channel" required>
                            <option value="email">email</option>
                            <option value="sms">sms</option>
                            <option value="whatsapp">whatsapp</option>
                            <option value="telegram">telegram</option>
                        </select>
                    </div>
                    <div class="col-md-4"><input class="form-control" name="reason" placeholder="Reason (optional)"></div>
                    <div class="col-md-1 d-grid"><button class="btn btn-primary">Add</button></div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr>
                    <th>Email</th><th>Phone</th><th>Channel</th><th>Reason</th><th>At</th><th></th>
                </tr></thead>
                <tbody>
                @forelse($items as $row)
                    <tr>
                        <td>{{ $row->email }}</td>
                        <td>{{ $row->phone }}</td>
                        <td>{{ $row->channel }}</td>
                        <td>{{ $row->reason }}</td>
                        <td>{{ $row->created_at }}</td>
                        <td class="text-end">
                            <form method="post" action="{{ route('customerconnect.settings.unsubscribes.destroy', $row->id) }}">
                                @csrf @method('delete')
                                <button class="btn btn-sm btn-outline-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted p-4">No unsubscribes yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $items->links() }}</div>
    </div>
</div>
@endsection
