<?php

namespace App\Http\Requests\Admin\Admin_user;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'phone' => ['nullable', 'string', 'max:20'],
            'role_id' => ['required', 'exists:tbl_roles,ID,active_status,1'],
            'designation' => ['nullable', 'string', 'max:255'],
            'profile_image' => [
                'nullable', 
                'image', 
                'mimes:jpeg,png,jpg,gif,svg', 
                new \App\Rules\NoExecutableFilesValidateRule()
            ],
            'address' => ['nullable', 'string'],
            'latitude' => ['nullable', 'string'],
            'longitude' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'active_status' => ['nullable', 'integer', 'in:0,1'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
