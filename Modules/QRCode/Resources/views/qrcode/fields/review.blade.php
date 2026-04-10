<div class="col-md-12">
    <div class="form-group my-3">
        <x-forms.label fieldId="url" :fieldLabel="__('qrcode::app.fields.reviewUrl')" :fieldRequired="true" />
        <input type="url" class="form-control height-35 f-14" placeholder="https://" name="url" id="url" value="{{ $formFields['url'] ?? '' }}">
    </div>
    <div class="form-group my-3">
        <x-forms.label fieldId="review_platform" :fieldLabel="__('qrcode::app.fields.reviewPlatform')" />
        <input type="text" class="form-control height-35 f-14" name="review_platform" id="review_platform" placeholder="e.g. Google" value="{{ $formFields['review_platform'] ?? '' }}">
    </div>
</div>
