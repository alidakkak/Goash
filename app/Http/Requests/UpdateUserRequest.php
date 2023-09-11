<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
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
            'first_name' => 'string|max:26',
            'last_name' => 'string|max:26',
            'email' => 'email|unique:users,email,'.Auth::user()->email,
            'points' => 'numeric|min:0',
            'image' => 'image|mimes:jpg,jpeg,png',
            'birthday' => 'date_format:Y-m-d'
        ];
    }
}
