<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        if ($this->has('is_hidden')) {
            // It's a hide action, only validate is_hidden
            return [
                'is_hidden' => ['required', 'in:0,1'],
            ];
        }
    
        // It's a full update, validate all fields
        return [
            'title' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9\s]+$/'],
            'content' => ['required', 'string', 'max:1000', 'regex:/^[A-Za-z0-9\s]+$/'],
            'type' => ['required', 'in:1,2,3'],
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.regex' => 'The title may only contain letters, numbers, and spaces.',
            'content.regex' => 'The description may only contain letters, numbers, and spaces.',
            'content.required' => 'The description field is required',
        ];
    }
}
