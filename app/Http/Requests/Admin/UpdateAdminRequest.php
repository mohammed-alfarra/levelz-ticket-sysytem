<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<string>>|array<string, string>
     */
    public function rules(): array
    {
        return [
            'name' => 'min:2|max:24',
            'email' => 'min:7|email|unique:admins,email,'.$this->admin->id,
            'password' => ['sometimes', 'string', 'min:8', 'confirmed'],
        ];
    }
}
