@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <x-cards.data :title="'Payment Links — Invoice #' . $invoice->invoice_number">

                <div class="mb-3">
                    <button id="generate-link-btn"
                            class="btn btn-primary"
                            data-invoice="{{ $invoice->id }}">
                        <i class="fa fa-link me-1"></i> Generate Payment Link
                    </button>
                </div>

                @if($links->isEmpty())
                    <p class="text-muted">No payment links generated for this invoice yet.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Gateway</th>
                                    <th>Status</th>
                                    <th>Expires</th>
                                    <th>Used At</th>
                                    <th>Link</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($links as $link)
                                    <tr>
                                        <td>{{ $link->id }}</td>
                                        <td>{{ ucfirst($link->gateway) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $link->status === 'active' ? 'success' : ($link->status === 'used' ? 'info' : 'secondary') }}">
                                                {{ ucfirst($link->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $link->expires_at ? $link->expires_at->diffForHumans() : '—' }}</td>
                                        <td>{{ $link->used_at ? $link->used_at->format('d M Y H:i') : '—' }}</td>
                                        <td>
                                            @if($link->isValid())
                                                <a href="{{ url('/pay/' . $link->token) }}" target="_blank"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fa fa-external-link-alt"></i> Open
                                                </a>
                                                <button class="btn btn-sm btn-outline-secondary copy-link-btn"
                                                        data-link="{{ url('/pay/' . $link->token) }}">
                                                    <i class="fa fa-copy"></i> Copy
                                                </button>
                                            @else
                                                <span class="text-muted">Expired / Used</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $links->links() }}
                @endif

            </x-cards.data>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$('#generate-link-btn').on('click', function () {
    const invoiceId = $(this).data('invoice');
    $.ajax({
        url: '/account/titanpay/links/' + invoiceId + '/generate',
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

$(document).on('click', '.copy-link-btn', function () {
    navigator.clipboard.writeText($(this).data('link'));
    $(this).text('Copied!').delay(1500).queue(function (next) {
        $(this).html('<i class="fa fa-copy"></i> Copy');
        next();
    });
});
</script>
@endpush
