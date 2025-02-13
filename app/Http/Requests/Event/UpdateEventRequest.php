<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|min:2',
            'description' => 'nullable',
            'quota' => 'required|integer|min:0',
            'start_date' => 'required|date_format:Y-m-d H:i:s|after:now',
            'end_date' => 'required|date_format:Y-m-d H:i:s|after:start_date',
        ];
    }
}
