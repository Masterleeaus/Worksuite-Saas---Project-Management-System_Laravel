<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Jobs Today</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f4f5f7; color: #1a1a2e; }
        .header { background: #2563eb; color: #fff; padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 100; }
        .header h1 { font-size: 1.1rem; font-weight: 700; }
        .header .date-label { font-size: 0.8rem; opacity: .8; }
        .job-list { padding: 16px; display: flex; flex-direction: column; gap: 14px; }
        .job-card { background: #fff; border-radius: 14px; padding: 18px 16px; box-shadow: 0 2px 10px rgba(0,0,0,.08); cursor: pointer; text-decoration: none; color: inherit; display: block; transition: transform .1s; }
        .job-card:active { transform: scale(.98); }
        .job-card-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; }
        .job-title { font-size: 1rem; font-weight: 700; }
        .status-badge { font-size: 0.7rem; font-weight: 600; padding: 3px 10px; border-radius: 999px; text-transform: uppercase; letter-spacing: .05em; }
        .status-incomplete { background: #fef3c7; color: #92400e; }
        .status-completed { background: #d1fae5; color: #065f46; }
        .status-checked-in { background: #dbeafe; color: #1d4ed8; }
        .job-meta { font-size: 0.82rem; color: #6b7280; margin-bottom: 6px; }
        .job-meta span { margin-right: 12px; }
        .job-project { font-size: 0.82rem; color: #6b7280; }
        .job-actions { margin-top: 12px; display: flex; gap: 10px; }
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 9px 18px; border-radius: 9px; font-size: 0.85rem; font-weight: 600; border: none; cursor: pointer; text-decoration: none; transition: opacity .15s; }
        .btn:active { opacity: .75; }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-outline { background: #fff; color: #2563eb; border: 1.5px solid #2563eb; }
        .btn-success { background: #16a34a; color: #fff; }
        .btn-sm { padding: 7px 14px; font-size: 0.78rem; }
        .no-jobs { text-align: center; padding: 60px 20px; color: #9ca3af; }
        .no-jobs svg { width: 64px; height: 64px; margin: 0 auto 16px; display: block; opacity: .4; }
        .no-jobs p { font-size: 1.05rem; }
        .maps-link { color: #2563eb; font-size: 0.82rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; }
        .alert { padding: 12px 16px; border-radius: 10px; margin: 0 16px 12px; font-size: 0.88rem; }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-danger { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="header h1">My Jobs Today</div>
            <div class="date-label">{{ now()->format('l, d M Y') }}</div>
        </div>
        <span>{{ auth()->user()->name }}</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">{{ implode(', ', $errors->all()) }}</div>
    @endif

    @if($jobs->isEmpty())
        <div class="no-jobs">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p>No jobs scheduled for today.</p>
        </div>
    @else
        <div class="job-list">
            @foreach($jobs as $job)
                @php
                    $checkedIn   = !empty($job->checked_in_at);
                    $checkedOut  = !empty($job->checked_out_at);
                    $statusClass = $job->status === 'completed' ? 'status-completed'
                                  : ($checkedIn ? 'status-checked-in' : 'status-incomplete');
                    $statusLabel = $job->status === 'completed' ? 'Complete'
                                  : ($checkedIn ? 'In Progress' : 'Pending');
                @endphp
                <a href="{{ route('cleaner.jobs.show', $job->id) }}" class="job-card">
                    <div class="job-card-header">
                        <div class="job-title">{{ $job->heading }}</div>
                        <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                    </div>
                    <div class="job-meta">
                        @if($job->due_date)
                            <span>🕐 {{ $job->due_date->format('H:i') }}</span>
                        @endif
                        @if($job->project)
                            <span>📂 {{ $job->project->project_name }}</span>
                        @endif
                    </div>
                    @if($checkedIn && $job->geofence_lat)
                        <a class="maps-link" target="_blank" rel="noopener noreferrer"
                           href="https://www.google.com/maps/dir/?api=1&destination={{ $job->geofence_lat }},{{ $job->geofence_lng }}"
                           onclick="event.stopPropagation()">
                            🗺 Navigate
                        </a>
                    @endif
                </a>
            @endforeach
        </div>
    @endif
</body>
</html>
