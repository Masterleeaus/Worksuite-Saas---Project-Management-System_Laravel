@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <x-cards.data :title="'QR Code #' . $qr->id">

                @if($qr->qr_image_path)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($qr->qr_image_path) }}"
                         alt="Payment QR Code"
                         class="img-fluid mb-3"
                         style="max-width:250px" />
                @elseif($imageHtml)
                    <div class="mb-3">{!! $imageHtml !!}</div>
                @else
                    <p class="text-muted">QR image not available.</p>
                @endif

                <p class="mb-1">
                    <strong>Status:</strong>
                    <span class="badge bg-{{ $qr->status === 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($qr->status) }}
                    </span>
                </p>
                <p class="mb-1"><strong>Scans:</strong> {{ $qr->scan_count }}</p>
                <p class="mb-1"><strong>Expires:</strong> {{ $qr->expires_at?->format('d M Y H:i') ?? '—' }}</p>
                <p class="mb-3">
                    <strong>Payment URL:</strong><br>
                    <a href="{{ $qr->payment_url }}" target="_blank">{{ $qr->payment_url }}</a>
                </p>

                <a href="{{ route('titanpay.qr.index', $qr->invoice_id) }}"
                   class="btn btn-outline-secondary btn-sm">
                    ← Back to QR list
                </a>

            </x-cards.data>
        </div>
    </div>
</div>
@endsection
