<?php

namespace Modules\TitanTheme\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MegaMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && in_array($this->user()->permission('manage_mega_menu'), ['all', 'added']);
    }

    public function rules(): array
    {
        return [
            'title'           => 'required|string|max:255',
            'slug'            => 'nullable|string|max:100',
            'icon'            => 'nullable|string|max:100',
            'sort_order'      => 'nullable|integer|min:0',
            'is_active'       => 'nullable|boolean',
            'required_module' => 'nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => __('titantheme::titantheme.mega_menu_title_required'),
            'title.max'      => __('titantheme::titantheme.mega_menu_title_max'),
        ];
    }
}
