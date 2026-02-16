<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAuthRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'staff_id' => 'required|string|exists:auths,staff_id',
            'password' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'staff_id.required' => 'Staff ID is required.',
            'staff_id.string' => 'Staff ID must be a string.',
            'staff_id.exists' => 'No user found with the provided Staff ID.',
            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a string.',
        ];
    }
}
