@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h3 class="mb-1">Customer Connect</h3>
            <p class="text-muted mb-0">Multi-channel campaigns (Email, SMS, WhatsApp, Telegram) — foundation pass.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-3">
            <a class="card" href="{{ route('customerconnect.campaigns.index') }}">
                <div class="card-body">
                    <strong>Campaigns</strong>
                    <div class="text-muted">Create & manage</div>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a class="card" href="{{ route('customerconnect.audiences.index') }}">
                <div class="card-body">
                    <strong>Audiences</strong>
                    <div class="text-muted">Lists & segments</div>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a class="card" href="{{ route('customerconnect.runs.index') }}">
                <div class="card-body">
                    <strong>Runs</strong>
                    <div class="text-muted">Execution history</div>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a class="card" href="{{ route('customerconnect.deliveries.index') }}">
                <div class="card-body">
                    <strong>Deliveries</strong>
                    <div class="text-muted">Channel logs</div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
