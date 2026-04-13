@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4>WhatsApp Channels</h4>
            <a href="{{ route('titanreach.whatsapp.create') }}" class="btn btn-success">+ Add Channel</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">Configured Channels</div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead><tr><th>Name</th><th>From Number</th><th>SID</th></tr></thead>
                        <tbody>
                            @forelse($channels as $ch)
                            <tr>
                                <td>{{ $ch->name }}</td>
                                <td>{{ $ch->from_number }}</td>
                                <td class="text-muted small">{{ Str::limit($ch->account_sid ?? '', 20) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted">No channels configured.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card">
                <div class="card-header">Send One-Off Message</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('titanreach.whatsapp.send') }}">
                        @csrf
                        <div class="form-group">
                            <label>To (WhatsApp number)</label>
                            <input type="text" name="to" class="form-control" placeholder="whatsapp:+1234567890" required>
                        </div>
                        <div class="form-group">
                            <label>Message</label>
                            <textarea name="body" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Media URL <small class="text-muted">(optional)</small></label>
                            <input type="url" name="media_url" class="form-control" placeholder="https://…">
                        </div>
                        <button class="btn btn-success w-100">Send WhatsApp</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
