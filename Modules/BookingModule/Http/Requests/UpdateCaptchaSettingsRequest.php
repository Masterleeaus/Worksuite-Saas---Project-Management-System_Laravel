<?php
namespace Modules\BookingModule\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCaptchaSettingsRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return []; }
}
