<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
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
            'name'=>'required|string|unique:roles,name',
            'description'=>'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'=>'Role name is required',
            'name.string'=>'Role name must be a string',
            'name.unique'=>'This Role name already exists. Role name must be unique',
            'description.string'=>'Description must be a string',
        ];
    }
}
