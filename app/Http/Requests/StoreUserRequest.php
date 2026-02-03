<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'first_name'=>'required|string|max:255',
            'last_name'=>'required|string|max:255',
            'staff_id'=>'required|string|unique:users,staff_id',
            'role_id'=>'required|integer|exists:roles,id',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required'=>'First name is required',
            'first_name.string'=>'First name must be a string',
            'first_name.max'=>'First name must not exceed 255 characters',
            'last_name.required'=>'Last name is required',
            'last_name.string'=>'Last name must be a string',
            'last_name.max'=>'Last name must not exceed 255 characters',
            'staff_id.required'=>'Staff ID is required',
            'staff_id.string'=>'Staff ID must be a string',
            'staff_id.unique'=>'This Staff ID already exists. Staff ID must be unique',
            'role_id.required'=>'Role ID is required',
            'role_id.integer'=>'Role ID must be an integer',
            'role_id.exists'=>'The selected Role ID does not exist',
        ];
    }
}
