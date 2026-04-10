<div class="col-md-12">
    <div class="form-group my-3">
        <x-forms.label fieldId="url" :fieldLabel="__('qrcode::app.fields.assetUrl')" :fieldRequired="true" />
        <input type="url" class="form-control height-35 f-14" placeholder="https://" name="url" id="url" value="{{ $formFields['url'] ?? '' }}">
    </div>
    <div class="form-group my-3">
        <x-forms.label fieldId="asset_label" :fieldLabel="__('qrcode::app.fields.assetLabel')" />
        <input type="text" class="form-control height-35 f-14" name="asset_label" id="asset_label" value="{{ $formFields['asset_label'] ?? '' }}">
    </div>
</div>
