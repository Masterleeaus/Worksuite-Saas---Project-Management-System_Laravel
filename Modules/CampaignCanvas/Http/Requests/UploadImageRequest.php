<?php
namespace Modules\CampaignCanvas\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'image' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp,svg',
                'max:' . (config('campaigncanvas.max_upload_size_mb', 8) * 1024),
            ],
        ];
    }
}
