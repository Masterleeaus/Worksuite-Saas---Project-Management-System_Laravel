@extends('layouts.app')

@push('styles')
<style>
.titanpay-settings .card-header { background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); color: #fff; }
</style>
@endpush

@section('content')
<div class="content-wrapper titanpay-settings">
    <div class="row">
        <div class="col-md-12">
            <x-cards.data :title="'TitanPay Settings'">

                <form id="titanpay-settings-form" method="POST"
                      action="{{ route('titanpay.settings.update') }}">
                    @csrf

                    {{-- ── Cryptomus ─────────────────────────────────── --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="fa fa-bitcoin me-2"></i> Cryptomus (Cryptocurrency Payments)
                            </h5>
                        </div>

                        <div class="col-md-6">
                            <x-forms.input
                                fieldId="cryptomus_merchant_uuid"
                                :fieldLabel="'Merchant UUID'"
                                fieldName="cryptomus_merchant_uuid"
                                :fieldValue="$settings['cryptomus_merchant_uuid']"
                                fieldPlaceholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"
                            />
                        </div>

                        <div class="col-md-6">
                            <x-forms.input
                                fieldId="cryptomus_payment_api_key"
                                :fieldLabel="'Payment API Key'"
                                fieldName="cryptomus_payment_api_key"
                                :fieldValue="$settings['cryptomus_payment_api_key']"
                                fieldType="password"
                            />
                        </div>

                        <div class="col-md-6">
                            <x-forms.input
                                fieldId="cryptomus_webhook_secret"
                                :fieldLabel="'Webhook Secret (optional)'"
                                fieldName="cryptomus_webhook_secret"
                                :fieldValue="$settings['cryptomus_webhook_secret']"
                                fieldType="password"
                            />
                        </div>

                        <div class="col-md-3">
                            <x-forms.input
                                fieldId="cryptomus_currency"
                                :fieldLabel="'Fiat Currency'"
                                fieldName="cryptomus_currency"
                                :fieldValue="$settings['cryptomus_currency']"
                                fieldPlaceholder="USD"
                            />
                        </div>

                        <div class="col-md-3 d-flex align-items-end pb-3">
                            <x-forms.checkbox
                                fieldId="cryptomus_active"
                                :fieldLabel="'Enable Cryptomus'"
                                fieldName="cryptomus_active"
                                fieldValue="1"
                                :checked="(bool)$settings['cryptomus_active']"
                            />
                        </div>

                        <div class="col-12">
                            <p class="text-muted small">
                                Webhook URL (add to Cryptomus dashboard):
                                <code>{{ route('titanpay.crypto.webhook') }}</code>
                            </p>
                        </div>
                    </div>

                    <hr>

                    {{-- ── Payment Link Settings ─────────────────────── --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="fa fa-link me-2"></i> Payment Links
                            </h5>
                        </div>

                        <div class="col-md-4">
                            <x-forms.input
                                fieldId="link_expiry_days"
                                :fieldLabel="'Link Expiry (days)'"
                                fieldName="link_expiry_days"
                                fieldType="number"
                                :fieldValue="$settings['link_expiry_days']"
                            />
                        </div>
                    </div>

                    <hr>

                    {{-- ── QR Code Settings ──────────────────────────── --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="fa fa-qrcode me-2"></i> QR Code Payments
                            </h5>
                        </div>

                        <div class="col-md-4">
                            <x-forms.input
                                fieldId="qr_expiry_days"
                                :fieldLabel="'QR Expiry (days)'"
                                fieldName="qr_expiry_days"
                                fieldType="number"
                                :fieldValue="$settings['qr_expiry_days']"
                            />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <x-forms.button-primary
                                id="save-settings"
                                class="mr-3"
                                :btnText="'Save Settings'"
                            />
                        </div>
                    </div>
                </form>

            </x-cards.data>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$('#titanpay-settings-form').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        success: function (resp) {
            if (resp.status === 'success') {
                Swal.fire({ icon: 'success', title: resp.message || 'Saved!', timer: 2000, showConfirmButton: false });
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: resp.message || 'Something went wrong.' });
            }
        },
        error: function () {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Request failed.' });
        }
    });
});
</script>
@endpush
