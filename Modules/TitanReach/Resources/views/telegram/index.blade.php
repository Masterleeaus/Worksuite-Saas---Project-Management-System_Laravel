@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4>Telegram Bots</h4>
            <a href="{{ route('titanreach.telegram.create') }}" class="btn btn-success">+ Add Bot</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">Configured Bots</div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead><tr><th>Name</th><th>Token (partial)</th><th>Webhook</th></tr></thead>
                        <tbody>
                            @forelse($bots as $bot)
                            <tr>
                                <td>{{ $bot->name }}</td>
                                <td class="text-muted small">{{ Str::limit($bot->bot_token ?? '', 20) }}</td>
                                <td class="small">{{ $bot->webhook_url ? Str::limit($bot->webhook_url, 40) : '—' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted">No bots configured.</td></tr>
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
                    <form method="POST" action="{{ route('titanreach.telegram.send') }}">
                        @csrf
                        <div class="form-group">
                            <label>Chat ID</label>
                            <input type="text" name="chat_id" class="form-control" placeholder="-100123456789" required>
                        </div>
                        <div class="form-group">
                            <label>Message</label>
                            <textarea name="text" class="form-control" rows="3" required></textarea>
                        </div>
                        <button class="btn btn-primary w-100">Send Telegram</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
