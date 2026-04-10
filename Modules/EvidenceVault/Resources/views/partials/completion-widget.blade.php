{{--
    Evidence Vault – Job Completion Widget (PWA / Mobile)
    =====================================================

    Include this partial inside any Blade view or modal that wraps
    the "mark job complete" action.

    Usage:
        @include('evidence_vault::partials.completion-widget', [
            'jobId'        => $job->id,
            'jobReference' => $job->reference,
        ])

    The widget will POST multipart/form-data to the Evidence Vault API
    endpoint before allowing the parent form to proceed.

    Dependencies (already present in Worksuite core):
        - jQuery
        - Bootstrap (modal, form, btn classes)
--}}

<div id="ev-completion-widget" class="evidence-vault-widget">

    {{-- ------------------------------------------------------------------ --}}
    {{-- Step 1: Photo upload                                                --}}
    {{-- ------------------------------------------------------------------ --}}
    <div class="ev-step" id="ev-step-photos">
        <h5 class="mb-3">
            <i class="fa fa-camera mr-2 text-primary"></i>
            Upload Photo Evidence
        </h5>
        <p class="f-13 text-dark-grey mb-3">
            Please take one or more photos of the cleaned area before completing this job.
        </p>

        <div class="ev-photo-dropzone text-center border rounded p-4 mb-3"
             id="ev-dropzone"
             style="cursor:pointer; border-style:dashed !important;">
            <i class="fa fa-cloud-upload fa-2x text-muted mb-2"></i>
            <p class="mb-1 f-14">Tap to select photos or drag and drop</p>
            <p class="f-12 text-muted">JPEG / PNG / WebP · max {{ config('evidence_vault.max_photo_kb', 10240) / 1024 }} MB each</p>
            <input type="file"
                   id="ev-photo-input"
                   accept="image/jpeg,image/png,image/webp"
                   multiple
                   capture="environment"
                   class="d-none">
        </div>

        <div id="ev-photo-preview" class="row mb-3"></div>

        {{-- Locked-site photo flag: applies to the whole submission.
             When checked, ALL uploaded photos are tagged is_site_locked_photo=true,
             serving as the alternative proof-of-completion in place of a client
             signature (e.g., for unmanned/locked premises). --}}
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="ev-is-locked-site-photo">
            <label class="form-check-label f-13" for="ev-is-locked-site-photo">
                These photos show the locked/secured site
                <small class="text-muted d-block mt-1">
                    Check this if the client is not present and you are using site photos as proof of completion.
                </small>
            </label>
        </div>

        <button type="button" class="btn btn-primary btn-sm" id="ev-next-to-signature">
            Next: Signature <i class="fa fa-arrow-right ml-1"></i>
        </button>
    </div>

    {{-- ------------------------------------------------------------------ --}}
    {{-- Step 2: Digital signature                                           --}}
    {{-- ------------------------------------------------------------------ --}}
    <div class="ev-step d-none" id="ev-step-signature">
        <h5 class="mb-3">
            <i class="fa fa-pencil mr-2 text-primary"></i>
            Client Signature
        </h5>
        <p class="f-13 text-dark-grey mb-3">
            Ask the client to sign below to confirm work completion.
            If the site is unattended, you may skip this step.
        </p>

        <div class="position-relative mb-3">
            <canvas id="ev-signature-canvas"
                    class="border rounded w-100"
                    style="touch-action:none; height:200px; background:#fff;">
            </canvas>
            <button type="button"
                    class="btn btn-xs btn-light position-absolute"
                    id="ev-clear-signature"
                    style="top:6px;right:6px;">
                <i class="fa fa-eraser"></i> Clear
            </button>
        </div>

        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-secondary btn-sm" id="ev-back-to-photos">
                <i class="fa fa-arrow-left mr-1"></i> Back
            </button>
            <button type="button" class="btn btn-success btn-sm" id="ev-submit-evidence">
                <i class="fa fa-check mr-1"></i> Submit Evidence &amp; Complete Job
            </button>
        </div>
    </div>

    {{-- ------------------------------------------------------------------ --}}
    {{-- Step 3: Uploading spinner                                           --}}
    {{-- ------------------------------------------------------------------ --}}
    <div class="ev-step d-none text-center py-5" id="ev-step-uploading">
        <div class="spinner-border text-primary mb-3" role="status">
            <span class="sr-only">Uploading…</span>
        </div>
        <p class="f-14">Uploading evidence, please wait…</p>
    </div>

    {{-- ------------------------------------------------------------------ --}}
    {{-- Step 4: Success                                                     --}}
    {{-- ------------------------------------------------------------------ --}}
    <div class="ev-step d-none text-center py-5" id="ev-step-success">
        <i class="fa fa-check-circle fa-3x text-success mb-3"></i>
        <h5 class="text-success">Evidence Submitted</h5>
        <p class="f-13 text-dark-grey" id="ev-success-message"></p>
    </div>

    {{-- Hidden field so parent forms can read the submission ID --}}
    <input type="hidden" id="ev-submission-id" name="evidence_submission_id">

</div>

@once
@push('scripts')
<script>
(function ($) {
    'use strict';

    // -----------------------------------------------------------------------
    // Config (passed from PHP)
    // -----------------------------------------------------------------------
    const API_SUBMIT_URL = '{{ route('api.evidence-vault.submit') }}';
    const JOB_ID         = {{ isset($jobId) ? (int)$jobId : 'null' }};
    const JOB_REF        = '{{ addslashes($jobReference ?? '') }}';
    const REQUIRE_PHOTO  = {{ config('evidence_vault.require_photo_on_completion', true) ? 'true' : 'false' }};
    const CSRF_TOKEN     = $('meta[name="csrf-token"]').attr('content');

    // -----------------------------------------------------------------------
    // State
    // -----------------------------------------------------------------------
    let selectedFiles   = [];
    let isDrawing       = false;
    let lastX           = 0;
    let lastY           = 0;
    let signatureCanvas = null;
    let ctx             = null;

    // -----------------------------------------------------------------------
    // Photo step
    // -----------------------------------------------------------------------
    $('#ev-dropzone').on('click', function () {
        $('#ev-photo-input').trigger('click');
    });

    $('#ev-dropzone').on('dragover', function (e) {
        e.preventDefault();
        $(this).addClass('border-primary');
    }).on('dragleave drop', function (e) {
        e.preventDefault();
        $(this).removeClass('border-primary');
        if (e.type === 'drop') {
            addFiles(e.originalEvent.dataTransfer.files);
        }
    });

    $('#ev-photo-input').on('change', function () {
        addFiles(this.files);
    });

    function addFiles(fileList) {
        Array.from(fileList).forEach(function (file) {
            if (!file.type.startsWith('image/')) { return; }
            selectedFiles.push(file);
            renderPreview(file, selectedFiles.length - 1);
        });
    }

    function renderPreview(file, idx) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const col = $('<div class="col-4 col-md-3 mb-2">');
            col.html(
                '<div class="position-relative">' +
                '<img src="' + e.target.result + '" class="img-fluid rounded" style="height:80px;object-fit:cover;width:100%;">' +
                '<button type="button" class="btn btn-xs btn-danger position-absolute ev-remove-photo" ' +
                    'data-idx="' + idx + '" style="top:2px;right:2px;padding:1px 5px;"><i class="fa fa-times"></i></button>' +
                '</div>'
            );
            $('#ev-photo-preview').append(col);
        };
        reader.readAsDataURL(file);
    }

    $(document).on('click', '.ev-remove-photo', function () {
        const idx = $(this).data('idx');
        selectedFiles.splice(idx, 1);
        $('#ev-photo-preview').empty();
        selectedFiles.forEach(function (file, i) { renderPreview(file, i); });
    });

    $('#ev-next-to-signature').on('click', function () {
        if (REQUIRE_PHOTO && selectedFiles.length === 0) {
            toastr.error('Please add at least one photo before continuing.');
            return;
        }
        showStep('signature');
        initCanvas();
    });

    // -----------------------------------------------------------------------
    // Signature canvas
    // -----------------------------------------------------------------------
    function initCanvas() {
        signatureCanvas = document.getElementById('ev-signature-canvas');
        if (!signatureCanvas) { return; }
        // Resize canvas to its CSS display size.
        signatureCanvas.width  = signatureCanvas.offsetWidth;
        signatureCanvas.height = signatureCanvas.offsetHeight;
        ctx = signatureCanvas.getContext('2d');
        ctx.strokeStyle = '#222';
        ctx.lineWidth   = 2;
        ctx.lineCap     = 'round';
    }

    function getPos(e) {
        const rect = signatureCanvas.getBoundingClientRect();
        const src  = e.touches ? e.touches[0] : e;
        return {
            x: src.clientX - rect.left,
            y: src.clientY - rect.top,
        };
    }

    $(document).on('mousedown touchstart', '#ev-signature-canvas', function (e) {
        e.preventDefault();
        isDrawing = true;
        const pos = getPos(e.originalEvent);
        [lastX, lastY] = [pos.x, pos.y];
    });

    $(document).on('mousemove touchmove', '#ev-signature-canvas', function (e) {
        e.preventDefault();
        if (!isDrawing) { return; }
        const pos = getPos(e.originalEvent);
        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
        [lastX, lastY] = [pos.x, pos.y];
    });

    $(document).on('mouseup touchend', '#ev-signature-canvas', function () {
        isDrawing = false;
    });

    $('#ev-clear-signature').on('click', function () {
        if (ctx) { ctx.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height); }
    });

    // -----------------------------------------------------------------------
    // Navigation
    // -----------------------------------------------------------------------
    $('#ev-back-to-photos').on('click', function () { showStep('photos'); });

    // -----------------------------------------------------------------------
    // Submit
    // -----------------------------------------------------------------------
    $('#ev-submit-evidence').on('click', function () {
        showStep('uploading');

        const formData = new FormData();
        if (JOB_ID)  { formData.append('job_id', JOB_ID); }
        if (JOB_REF) { formData.append('job_reference', JOB_REF); }

        // The locked-site flag is submission-level: it applies to all uploaded
        // photos when the "locked site" checkbox is checked.
        const isLockedSite = $('#ev-is-locked-site-photo').is(':checked') ? '1' : '0';
        selectedFiles.forEach(function (file) {
            formData.append('photos[]', file);
            formData.append('is_site_locked_photo[]', isLockedSite);
        });

        // Signature
        const sigIsEmpty = isCanvasBlank(signatureCanvas);
        if (!sigIsEmpty && signatureCanvas) {
            formData.append('signature_data', signatureCanvas.toDataURL('image/png'));
            formData.append('client_signed', '1');
        }

        $.ajax({
            url        : API_SUBMIT_URL,
            type       : 'POST',
            data       : formData,
            processData: false,
            contentType: false,
            headers    : { 'X-CSRF-TOKEN': CSRF_TOKEN },
            success: function (response) {
                $('#ev-submission-id').val(response.submission_id || '');
                $('#ev-success-message').text(
                    response.photo_count + ' photo(s) uploaded successfully. Submission #' + response.submission_id + '.'
                );
                showStep('success');
                // Dispatch a custom event so parent pages can react.
                $(document).trigger('evidenceVaultSubmitted', [response]);
            },
            error: function (xhr) {
                showStep('signature');
                let msg = 'Upload failed. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    msg = Object.values(xhr.responseJSON.errors).flat().join(' ');
                }
                toastr.error(msg);
            },
        });
    });

    // -----------------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------------
    function showStep(name) {
        $('.ev-step').addClass('d-none');
        $('#ev-step-' + name).removeClass('d-none');
    }

    function isCanvasBlank(canvas) {
        if (!canvas) { return true; }
        const blank = document.createElement('canvas');
        blank.width  = canvas.width;
        blank.height = canvas.height;
        return canvas.toDataURL() === blank.toDataURL();
    }

}(jQuery));
</script>
@endpush
@endonce
