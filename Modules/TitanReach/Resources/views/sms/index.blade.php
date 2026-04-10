@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4>SMS Numbers</h4>
            <a href="{{ route('titanreach.sms.create') }}" class="btn btn-success">+ Add Number</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Configured SMS Numbers</div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead><tr><th>Name</th><th>Phone Number</th><th>Account SID</th><th>Active</th></tr></thead>
                        <tbody>
                            @forelse($numbers as $n)
                            <tr>
                                <td>{{ $n->name }}</td>
                                <td>{{ $n->phone_number }}</td>
                                <td>{{ $n->account_sid ? Str::limit($n->account_sid, 20) : '—' }}</td>
                                <td>{{ $n->active ? 'Yes' : 'No' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted">No numbers configured.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Send One-Off SMS</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('titanreach.sms.send') }}">
                        @csrf
                        <div class="form-group">
                            <label>To</label>
                            <input type="text" name="to" class="form-control" placeholder="+1234567890" required>
                        </div>
                        <div class="form-group">
                            <label>Message</label>
                            <textarea name="body" class="form-control" rows="3" required></textarea>
                        </div>
                        <button class="btn btn-primary w-100">Send SMS</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
