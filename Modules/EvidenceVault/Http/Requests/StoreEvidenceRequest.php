<?php

namespace Modules\EvidenceVault\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvidenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $maxKb    = (int) config('evidence_vault.max_photo_kb', 10240);
        // Normalise: config may store full MIME types ('image/jpeg') or short
        // names ('jpeg').  Strip the 'image/' prefix when present so that
        // Laravel's 'mimes' validation rule always receives short names.
        $mimes    = implode(',', array_map(
            fn($m) => preg_replace('/^image\//', '', $m),
            config('evidence_vault.allowed_photo_types', ['jpeg', 'png', 'webp'])
        ));

        return [
            'job_id'            => ['nullable', 'integer'],
            'job_reference'     => ['nullable', 'string', 'max:255'],
            'notes'             => ['nullable', 'string', 'max:2000'],
            'signature_data'    => ['nullable', 'string'],   // base64 PNG data-URI
            'client_signed'     => ['nullable', 'boolean'],

            // At least one photo is required when the config flag is enabled.
            'photos'            => [
                config('evidence_vault.require_photo_on_completion', true) ? 'required' : 'nullable',
                'array',
                'min:1',
            ],
            'photos.*'          => [
                'required',
                'file',
                'mimes:' . $mimes,
                'max:' . $maxKb,
            ],

            'is_site_locked_photo.*' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'photos.required' => 'At least one photo of the cleaned area is required to complete this job.',
            'photos.*.mimes'  => 'Each photo must be a JPEG, PNG, or WebP image.',
            'photos.*.max'    => 'Each photo must not exceed ' . config('evidence_vault.max_photo_kb', 10240) . ' KB.',
        ];
    }
}
