@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Recipes</h4>
        <a href="{{ route('customerconnect.campaigns.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="row g-3">
        @foreach($recipes as $r)
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="h5">{{ $r['name'] }}</div>
                        <div class="text-muted mb-2">Key: {{ $r['key'] }}</div>
                        <ul class="mb-3">
                            @foreach($r['steps'] as $s)
                                <li>{{ $s['channel'] }} — {{ $s['name'] }} (delay {{ $s['delay_minutes'] }} mins)</li>
                            @endforeach
                        </ul>
                        <form method="post" action="{{ route('customerconnect.recipes.install') }}">
                            @csrf
                            <input type="hidden" name="key" value="{{ $r['key'] }}">
                            <button class="btn btn-primary">Install as draft</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
