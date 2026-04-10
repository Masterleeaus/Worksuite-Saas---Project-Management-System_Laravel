<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Job: {{ $job->heading }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f4f5f7; color: #1a1a2e; padding-bottom: 40px; }
        .header { background: #2563eb; color: #fff; padding: 14px 16px; display: flex; align-items: center; gap: 12px; position: sticky; top: 0; z-index: 100; }
        .header a { color: #fff; text-decoration: none; font-size: 1.2rem; }
        .header h1 { font-size: 1rem; font-weight: 700; }
        .section { background: #fff; border-radius: 14px; margin: 14px 14px 0; padding: 18px 16px; box-shadow: 0 2px 8px rgba(0,0,0,.07); }
        .section-title { font-size: 0.78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #6b7280; margin-bottom: 12px; }
        .status-row { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
        .status-badge { font-size: 0.75rem; font-weight: 700; padding: 4px 12px; border-radius: 999px; text-transform: uppercase; }
        .status-incomplete { background: #fef3c7; color: #92400e; }
        .status-completed { background: #d1fae5; color: #065f46; }
        .status-checked-in { background: #dbeafe; color: #1d4ed8; }
        .meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; font-size: 0.85rem; }
        .meta-item label { display: block; font-size: 0.72rem; color: #9ca3af; margin-bottom: 2px; }
        .meta-item span { font-weight: 600; }
        .btn { display: flex; align-items: center; justify-content: center; padding: 13px; border-radius: 12px; font-size: 0.95rem; font-weight: 700; border: none; cursor: pointer; text-decoration: none; transition: opacity .15s; width: 100%; }
        .btn:active { opacity: .75; }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-success { background: #16a34a; color: #fff; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-outline { background: #fff; color: #2563eb; border: 1.5px solid #2563eb; }
        .btn-sm { padding: 9px 14px; font-size: 0.82rem; border-radius: 9px; width: auto; }
        .gps-status { font-size: 0.82rem; color: #6b7280; margin-top: 6px; min-height: 1.2em; }
        .hidden { display: none; }
        /* Checklist */
        .checklist-item { display: flex; align-items: center; gap: 12px; padding: 11px 0; border-bottom: 1px solid #f3f4f6; }
        .checklist-item:last-child { border-bottom: none; }
        .check-circle { width: 26px; height: 26px; border-radius: 50%; border: 2px solid #d1d5db; display: flex; align-items: center; justify-content: center; flex-shrink: 0; cursor: pointer; transition: all .15s; }
        .check-circle.done { background: #16a34a; border-color: #16a34a; color: #fff; }
        .check-label { font-size: 0.9rem; flex: 1; }
        .check-label.done { text-decoration: line-through; color: #9ca3af; }
        .progress-bar-wrap { background: #e5e7eb; border-radius: 999px; height: 7px; overflow: hidden; margin-top: 8px; }
        .progress-bar-fill { background: #16a34a; height: 100%; border-radius: 999px; transition: width .3s; }
        /* Photos */
        .photo-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .photo-thumb { position: relative; aspect-ratio: 1; border-radius: 10px; overflow: hidden; background: #f3f4f6; }
        .photo-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .photo-type-badge { position: absolute; bottom: 6px; left: 6px; background: rgba(0,0,0,.55); color: #fff; font-size: 0.65rem; font-weight: 700; padding: 2px 7px; border-radius: 999px; text-transform: uppercase; }
        .photo-upload-form { margin-top: 14px; }
        .form-group { margin-bottom: 12px; }
        .form-group label { display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 5px; color: #374151; }
        .form-control { width: 100%; padding: 9px 12px; border: 1.5px solid #d1d5db; border-radius: 9px; font-size: 0.88rem; background: #fff; }
        select.form-control { appearance: none; }
        .alert { padding: 11px 14px; border-radius: 10px; margin: 12px 14px 0; font-size: 0.88rem; }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-danger { background: #fee2e2; color: #991b1b; }
        .maps-link { display: inline-flex; align-items: center; gap: 5px; color: #2563eb; font-weight: 600; text-decoration: none; font-size: 0.88rem; padding: 8px 0; }
        .divider { border: none; border-top: 1px solid #f3f4f6; margin: 12px 0; }
        .checkin-time { font-size: 0.8rem; color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <a href="{{ route('cleaner.jobs.index') }}">&#8592;</a>
        <h1>{{ Str::limit($job->heading, 40) }}</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">{{ implode(' ', $errors->all()) }}</div>
    @endif

    @php
        $checkedIn  = !empty($job->checked_in_at);
        $checkedOut = !empty($job->checked_out_at);
        $statusClass = $job->status === 'completed' ? 'status-completed'
                      : ($checkedIn ? 'status-checked-in' : 'status-incomplete');
        $statusLabel = $job->status === 'completed' ? 'Complete'
                      : ($checkedIn ? 'In Progress' : 'Pending');
    @endphp

    {{-- Job Summary --}}
    <div class="section">
        <div class="section-title">Job Details</div>
        <div class="status-row" style="margin-bottom:12px">
            <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
            @if($job->priority)
                <span style="font-size:.75rem;color:#6b7280;">Priority: {{ ucfirst($job->priority) }}</span>
            @endif
        </div>
        <div class="meta-grid">
            @if($job->due_date)
                <div class="meta-item">
                    <label>Scheduled</label>
                    <span>{{ $job->due_date->format('d M Y, H:i') }}</span>
                </div>
            @endif
            @if($job->project)
                <div class="meta-item">
                    <label>Project</label>
                    <span>{{ $job->project->project_name }}</span>
                </div>
            @endif
            @if($checkedIn)
                <div class="meta-item">
                    <label>Checked In</label>
                    <span>{{ \Carbon\Carbon::parse($job->checked_in_at)->format('H:i') }}</span>
                </div>
            @endif
            @if($checkedOut)
                <div class="meta-item">
                    <label>Checked Out</label>
                    <span>{{ \Carbon\Carbon::parse($job->checked_out_at)->format('H:i') }}</span>
                </div>
            @endif
        </div>

        @if($job->geofence_lat)
            <hr class="divider">
            <a class="maps-link" target="_blank" rel="noopener noreferrer"
               href="https://www.google.com/maps/dir/?api=1&destination={{ $job->geofence_lat }},{{ $job->geofence_lng }}">
                🗺 Navigate to Job
            </a>
        @endif

        @if($job->description)
            <hr class="divider">
            <div style="font-size:.88rem;color:#4b5563;">{!! nl2br(e($job->description)) !!}</div>
        @endif
    </div>

    {{-- GPS Check-in / Check-out --}}
    @if(!$checkedIn)
    <div class="section">
        <div class="section-title">Check In</div>
        <form id="checkin-form" action="{{ route('cleaner.jobs.check-in', $job->id) }}" method="POST">
            @csrf
            <input type="hidden" name="latitude" id="ci_lat">
            <input type="hidden" name="longitude" id="ci_lng">
            <div class="gps-status" id="gps-status">Tap to capture your GPS location…</div>
            <div style="height:10px"></div>
            <button type="button" class="btn btn-primary" id="btn-checkin" onclick="captureGPS('checkin-form','ci_lat','ci_lng','gps-status')">
                📍 Check In with GPS
            </button>
        </form>
    </div>
    @elseif(!$checkedOut)
    <div class="section">
        <div class="section-title">Check Out</div>
        <div class="checkin-time">Checked in at {{ \Carbon\Carbon::parse($job->checked_in_at)->format('H:i') }}</div>
        <div style="height:10px"></div>
        <form id="checkout-form" action="{{ route('cleaner.jobs.check-out', $job->id) }}" method="POST">
            @csrf
            <input type="hidden" name="latitude" id="co_lat">
            <input type="hidden" name="longitude" id="co_lng">
            <div class="gps-status" id="gps-status-co">Tap to capture your GPS location…</div>
            <div style="height:10px"></div>
            <button type="button" class="btn btn-success" onclick="captureGPS('checkout-form','co_lat','co_lng','gps-status-co')">
                ✅ Check Out &amp; Complete Job
            </button>
        </form>
    </div>
    @else
    <div class="section">
        <div class="section-title">Job Complete</div>
        <div style="font-size:.9rem;color:#16a34a;font-weight:600;">✅ You checked out at {{ \Carbon\Carbon::parse($job->checked_out_at)->format('H:i') }}</div>
    </div>
    @endif

    {{-- Checklists --}}
    @foreach($checklists as $checklist)
    @php $pct = $checklist->completion_percentage; @endphp
    <div class="section">
        <div class="section-title" style="display:flex;justify-content:space-between;align-items:center;">
            <span>{{ $checklist->title }}</span>
            <span style="font-size:.8rem;color:#374151;">{{ $pct }}%</span>
        </div>
        <div class="progress-bar-wrap">
            <div class="progress-bar-fill" style="width:{{ $pct }}%"></div>
        </div>
        <div style="height:10px"></div>
        @foreach($checklist->items as $item)
        <div class="checklist-item">
            <form action="{{ route('cleaner.checklist-items.toggle', $item->id) }}" method="POST" style="display:contents">
                @csrf
                <button type="submit" class="check-circle {{ $item->is_completed ? 'done' : '' }}" title="{{ $item->is_completed ? 'Mark incomplete' : 'Mark complete' }}">
                    @if($item->is_completed) ✓ @endif
                </button>
                <span class="check-label {{ $item->is_completed ? 'done' : '' }}">{{ $item->label }}</span>
            </form>
        </div>
        @endforeach
    </div>
    @endforeach

    {{-- Photos --}}
    <div class="section">
        <div class="section-title">Photos</div>

        @if($photos->isNotEmpty())
            <div class="photo-grid" style="margin-bottom:16px">
                @foreach($photos as $photo)
                <div class="photo-thumb">
                    <img src="{{ asset('storage/' . $photo->file_path) }}" alt="{{ $photo->type }}" loading="lazy">
                    <span class="photo-type-badge">{{ $photo->type }}</span>
                </div>
                @endforeach
            </div>
        @endif

        <details>
            <summary style="font-size:.88rem;font-weight:600;color:#2563eb;cursor:pointer;padding:4px 0;">+ Add Photo</summary>
            <div class="photo-upload-form">
                <form action="{{ route('cleaner.jobs.photos.upload', $job->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="photo_type">Type</label>
                        <select name="type" id="photo_type" class="form-control">
                            <option value="before">Before</option>
                            <option value="after">After</option>
                            <option value="damage">Damage</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="photo_file">Photo</label>
                        <input type="file" name="photo" id="photo_file" accept="image/*" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="photo_caption">Caption (optional)</label>
                        <input type="text" name="caption" id="photo_caption" class="form-control" maxlength="255">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm" style="width:100%">Upload Photo</button>
                </form>
            </div>
        </details>
    </div>

    <script>
    /**
     * Capture the device GPS location and populate hidden form fields, then submit.
     */
    function captureGPS(formId, latFieldId, lngFieldId, statusId) {
        var statusEl = document.getElementById(statusId);
        statusEl.textContent = 'Locating…';

        if (!navigator.geolocation) {
            statusEl.textContent = 'Geolocation is not supported by your browser.';
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function(position) {
                document.getElementById(latFieldId).value = position.coords.latitude;
                document.getElementById(lngFieldId).value = position.coords.longitude;
                statusEl.textContent = 'Location captured (' +
                    position.coords.latitude.toFixed(5) + ', ' +
                    position.coords.longitude.toFixed(5) + ')';
                document.getElementById(formId).submit();
            },
            function(error) {
                var msgs = {
                    1: 'Location permission denied. Please allow location access.',
                    2: 'Position unavailable. Please try again.',
                    3: 'Location request timed out. Please try again.'
                };
                statusEl.textContent = msgs[error.code] || 'Unable to get location.';
            },
            { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
        );
    }
    </script>
</body>
</html>
