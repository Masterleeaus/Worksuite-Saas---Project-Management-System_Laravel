@extends('layouts.app')

@section('content')
<div class="content container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Booking page requests</h2>
            <p class="text-muted mb-0">Leads captured from public booking pages and portal entry pages.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table align-middle">
                <thead><tr><th>ID</th><th>Page</th><th>Customer</th><th>Service</th><th>Preferred slot</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @forelse($requests as $requestRow)
                    <tr>
                        <td>#{{ $requestRow->id }}</td>
                        <td>{{ $requestRow->page_slug ?: '—' }}</td>
                        <td>
                            <strong>{{ $requestRow->customer_name }}</strong><br>
                            <span class="text-muted">{{ $requestRow->phone }} @if($requestRow->email) · {{ $requestRow->email }} @endif</span>
                        </td>
                        <td>{{ $requestRow->service_name ?: '—' }}</td>
                        <td>{{ optional($requestRow->preferred_date)->format('d M Y') ?: '—' }} @if($requestRow->preferred_window) · {{ $requestRow->preferred_window }} @endif</td>
                        <td>
                            <form method="POST" action="{{ route('admin.booking.page-requests.status', $requestRow) }}" class="d-flex gap-2">
                                @csrf
                                @method('PUT')
                                <select name="status" class="form-select form-select-sm">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}" @selected($requestRow->status === $status)>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-sm btn-primary">Save</button>
                            </form>
                        </td>
                        <td class="text-muted" style="max-width:280px;">{{ \Illuminate\Support\Str::limit($requestRow->notes, 80) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-5">No booking page requests captured yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($requests, 'links'))<div class="card-footer">{{ $requests->links() }}</div>@endif
    </div>
</div>
@endsection
