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
        // return [
        //     //
        //     'staff_id' => 'required|string|exists:users,staff_id|unique:auths,staff_id',
        //     'password' => ['required',Password::defaults()],
        // ];
        return [
        'staff_id' => 'required|string|exists:auths,staff_id',
        'old_password' => ['nullable','string',Password::defaults()], // Optional for first-time reset
        'new_password' => ['required','confirmed',Password::defaults()]
    ];
    }


    public function messages()
    {
        return[
            'staff_id.required'=>'the staff id is required',
            'Staff_id.string'=>'the staff id must be a string',
            'staff_id.exists'=>'the staff id does not exist',
            'old_password.string'=>'the old password must be a string',
            'new_password.required'=>'the new password is required',
            'new_password.confirmed'=>'the new password confirmation does not match',

        ];

    }
}
