<?php

namespace App\Http\Requests;

class UpdateThemeSetting extends CoreRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'primary_color.*' => [
                'required',
                'regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i'
            ],
            'global_header_color' => [
                'required',
                'regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i'
            ],
            'sidebar_color' => ['nullable', 'regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i'],
            'sidebar_text_color' => ['nullable', 'regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i'],
            'link_color' => ['nullable', 'regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i'],
            'user_css' => ['nullable', 'string'],
            'enable_rounded_theme' => ['nullable', 'boolean'],
            'app_name' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'primary_color.*.required' => __('messages.primaryColorRequired'),
        ];
    }

}
