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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:28',
            'last_name' => 'required|string|max:28',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:7|max:28',
            'phone' => 'required',
            'image' => 'image|mimes:jpg,png,jpeg',
            'birthday' => 'required|date_format:Y-m-d',
            'device_key' => 'required'
        ];
    }
}
