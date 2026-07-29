<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendSmsRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'to'   => ['required', 'array', 'min:1', 'max:100'],
            'to.*' => ['required', 'string', 'regex:/^(\+?\d{7,15})$/'],
            'text' => ['required', 'string', 'max:918'], // ~6 SMS segments
            'is_intl' => ['sometimes', 'boolean'],
        ];
    }
}
