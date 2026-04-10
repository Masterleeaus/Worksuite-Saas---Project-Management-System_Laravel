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
            // Accept either client_name or customer_name; at least one is required
            'client_name'   => 'nullable|string|max:255',
            'customer_name' => 'nullable|string|max:255',
            'position'      => 'nullable|string|max:255',
            'description'   => 'nullable|string',
            'content'       => 'nullable|string',
            'status'        => 'nullable|in:0,1',
            // Cleaning-business fields
            'suburb'        => 'nullable|string|max:100',
            'service_type'  => 'nullable|string|max:100',
            'star_rating'   => 'nullable|integer|min:1|max:5',
            'video_url'     => 'nullable|url|max:500',
            'before_photo'  => 'nullable|mimes:jpg,jpeg,png,webp|max:4096',
            'after_photo'   => 'nullable|mimes:jpg,jpeg,png,webp|max:4096',
        ];

        if ($this->input('method') == 'add') {
            // Require at least one of client_name / customer_name and some testimonial text
            $rules['client_name']   = 'required_without:customer_name|nullable|string|max:255';
            $rules['customer_name'] = 'required_without:client_name|nullable|string|max:255';
            $rules['description']   = 'required_without:content|nullable|string';
            $rules['content']       = 'required_without:description|nullable|string';
            $rules['client_image']  = 'nullable|mimes:jpg,jpeg,png,webp|max:2048';
        } else {
            $rules['id']            = 'required|exists:testimonials,id';
            $rules['client_image']  = 'nullable|mimes:jpg,jpeg,png,webp|max:2048';
        }

        return $rules;
    }
}


