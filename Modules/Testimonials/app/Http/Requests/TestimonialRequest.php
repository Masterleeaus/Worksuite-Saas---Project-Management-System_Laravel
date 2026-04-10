<?php

namespace Modules\Testimonials\app\Http\Requests;

use App\Library\CustomFailedValidation;

class TestimonialRequest extends CustomFailedValidation
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'client_name'  => 'required|string|max:255',
            'position'     => 'nullable|string|max:255',
            'description'  => 'required|string',
            'status'       => 'nullable|in:0,1',
            // New cleaning-business fields
            'suburb'       => 'nullable|string|max:100',
            'service_type' => 'nullable|string|max:100',
            'content'      => 'nullable|string',
            'star_rating'  => 'nullable|integer|min:1|max:5',
            'video_url'    => 'nullable|url|max:500',
            'before_photo' => 'nullable|mimes:jpg,jpeg,png,webp|max:4096',
            'after_photo'  => 'nullable|mimes:jpg,jpeg,png,webp|max:4096',
        ];

        if ($this->input('method') == 'add') {
            $rules['client_image'] = 'nullable|mimes:jpg,jpeg,png,webp|max:2048';
        } else {
            $rules['id']           = 'required|exists:testimonials,id';
            $rules['client_image'] = 'nullable|mimes:jpg,jpeg,png,webp|max:2048';
        }

        return $rules;
    }
}

