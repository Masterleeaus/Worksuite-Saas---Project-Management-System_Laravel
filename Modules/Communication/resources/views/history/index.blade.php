@extends('communication::layouts.master')

@section('communication_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">
        <i class="bi bi-clock-history me-2"></i>Communication History
    </h2>
    <a href="{{ route('communications.compose') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-pencil-square me-1"></i>Compose
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Channel</th>
                    <th>To</th>
                    <th>Subject / Preview</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($communications as $msg)
                <tr>
                    <td>
                        @php
                            $icons = ['email'=>'envelope','sms'=>'chat-dots','chat'=>'chat','push'=>'bell'];
                            $icon = $icons[$msg->type] ?? 'question-circle';
                        @endphp
                        <i class="bi bi-{{ $icon }} me-1"></i>{{ ucfirst($msg->type) }}
                    </td>
                    <td class="text-truncate" style="max-width:160px;">{{ $msg->to_address }}</td>
                    <td class="text-truncate" style="max-width:280px;">
                        @if($msg->subject)<strong>{{ $msg->subject }}</strong><br>@endif
                        <small class="text-muted">{{ IlluminateSupportStr::limit(strip_tags($msg->body), 80) }}</small>
                    </td>
                    <td>
                        @php $badgeMap = ['queued'=>'secondary','sent'=>'info','delivered'=>'primary','failed'=>'danger','read'=>'success']; @endphp
                        <span class="badge bg-{{ $badgeMap[$msg->status] ?? 'light' }}">{{ ucfirst($msg->status) }}</span>
                    </td>
                    <td>{{ $msg->created_at->format('d M Y H:i') }}</td>
                    <td><a href="{{ route('communications.show', $msg->id) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">No communication history found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($communications->hasPages())
    <div class="card-footer">
        {{ $communications->links() }}
    </div>
    @endif
</div>
@endsection
