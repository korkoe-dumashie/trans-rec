<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
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
            //
            'staff_id' => 'required|string|exists:users,staff_id|unique:auths,staff_id',
            'password' => ['required',Password::defaults()],
        ];
    }


    public function messages()
    {
        return[
            'staff_id.required'=>'This staff does not exist',
            'staff_id.string'=>'Invalid staff ID format',
            'staff_id.unique'=>'This staff already has already reset their password',
            'password.required'=>'Password is required',
            'password.string'=>'Invalid password format',
            'password.min'=>'Password must be at least 8 characters',
            'password.confirmed'=>'Password confirmation does not match',
            
        ];

    }
}
