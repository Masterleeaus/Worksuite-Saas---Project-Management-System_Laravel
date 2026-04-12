@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <x-cards.data :title="'Payment QR Codes — Invoice #' . $invoice->invoice_number">

                <div class="mb-3">
                    <button id="generate-qr-btn"
                            class="btn btn-primary"
                            data-invoice="{{ $invoice->id }}">
                        <i class="fa fa-qrcode me-1"></i> Generate QR Code
                    </button>
                </div>

                @if($qrCodes->isEmpty())
                    <p class="text-muted">No QR codes generated for this invoice yet.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Status</th>
                                    <th>Scans</th>
                                    <th>Expires</th>
                                    <th>Paid At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($qrCodes as $qr)
                                    <tr>
                                        <td>{{ $qr->id }}</td>
                                        <td>
                                            <span class="badge bg-{{ $qr->status === 'active' ? 'success' : ($qr->status === 'used' ? 'info' : 'secondary') }}">
                                                {{ ucfirst($qr->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $qr->scan_count }}</td>
                                        <td>{{ $qr->expires_at ? $qr->expires_at->diffForHumans() : '—' }}</td>
                                        <td>{{ $qr->paid_at ? $qr->paid_at->format('d M Y H:i') : '—' }}</td>
                                        <td>
                                            <a href="{{ route('titanpay.qr.show', $qr->id) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fa fa-eye"></i> View QR
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $qrCodes->links() }}
                @endif

            </x-cards.data>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$('#generate-qr-btn').on('click', function () {
    const invoiceId = $(this).data('invoice');
    $.ajax({
        url: '/account/titanpay/qr/' + invoiceId + '/generate',
        method: 'POST',
        data: { _token: '{{ csrf_token() }}' },
        success: function (resp) {
            if (resp.status === 'success') {
                location.reload();
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: resp.message });
            }
        },
        error: function () {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Request failed.' });
        }
    });
});
</script>
@endpush
