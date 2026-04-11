<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('projectroadmap::app.roadmap.publicTitle') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body { background: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .roadmap-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; padding: 60px 0 40px; }
        .status-col .card-header { font-weight: 700; font-size: 14px; }
        .roadmap-item { border-left: 4px solid #dee2e6; transition: box-shadow .2s; }
        .roadmap-item:hover { box-shadow: 0 4px 12px rgba(0,0,0,.08); }
        .vote-badge { font-size: 12px; }
    </style>
</head>
<body>
    <div class="roadmap-header text-center mb-5">
        <h1 class="display-5">{{ __('projectroadmap::app.roadmap.publicTitle') }}</h1>
        <p class="lead mb-0">Vote on features you'd like to see built next.</p>
    </div>

    <div class="container pb-5">
        <div class="row">
            @foreach($statuses as $key => $label)
                @php $colItems = $items[$key] ?? collect(); @endphp
                <div class="col-md-2 mb-4 status-col">
                    <div class="card h-100">
                        <div class="card-header bg-{{ $statusColors[$key] ?? 'secondary' }} text-white text-center py-2">
                            {{ $label }}
                            <span class="badge badge-light ml-1">{{ $colItems->count() }}</span>
                        </div>
                        <div class="card-body p-2">
                            @forelse($colItems as $item)
                                <div class="card roadmap-item mb-2 border-{{ $statusColors[$key] ?? 'secondary' }}">
                                    <div class="card-body p-2">
                                        <h6 class="mb-1" style="font-size:13px;font-weight:600;">{{ $item->name }}</h6>
                                        @if($item->category)
                                            <span class="badge badge-light" style="font-size:10px;">{{ $item->category }}</span>
                                        @endif
                                        @if($item->description)
                                            <p class="text-muted mb-1" style="font-size:11px;">{{ Str::limit($item->description, 80) }}</p>
                                        @endif
                                        @if($item->target_release)
                                            <p class="text-muted mb-1" style="font-size:11px;">
                                                📅 {{ $item->target_release }}
                                            </p>
                                        @endif
                                        <div class="d-flex justify-content-end">
                                            <span class="badge badge-secondary vote-badge">
                                                👍 {{ $item->votes }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-muted" style="font-size:11px;margin-top:16px;">
                                    Nothing here yet.
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
