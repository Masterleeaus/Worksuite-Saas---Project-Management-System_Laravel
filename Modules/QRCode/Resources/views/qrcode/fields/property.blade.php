<div class="col-md-12">
    <div class="form-group my-3">
        <x-forms.label fieldId="url" :fieldLabel="__('qrcode::app.fields.propertyUrl')" :fieldRequired="true" />
        <input type="url" class="form-control height-35 f-14" placeholder="https://" name="url" id="url" value="{{ $formFields['url'] ?? '' }}">
    </div>
    <div class="form-group my-3">
        <x-forms.label fieldId="property_label" :fieldLabel="__('qrcode::app.fields.propertyLabel')" />
        <input type="text" class="form-control height-35 f-14" name="property_label" id="property_label" value="{{ $formFields['property_label'] ?? '' }}">
    </div>
</div>
